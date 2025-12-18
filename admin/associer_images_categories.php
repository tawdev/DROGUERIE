<?php
/**
 * Script pour associer des images aux catégories
 */
require_once 'auth.php';
require_once '../config/config.php';

$page_title = 'Associer images aux catégories';

// Dossier source des images
$source_dir = ROOT_PATH . '/assets/image/proudact/';
// Dossier de destination
$dest_dir = UPLOAD_PATH;

$errors = [];
$success = [];
$associations = [];

// Mapping des catégories vers les images (basé sur les mots-clés)
$mapping_categories = [
    'Matériaux de construction' => [
        'plaque.*plâtre|plâtre.*plaque',
        'laine.*verre|verre.*laine|panneau.*roulé.*laine',
        'chaux.*aérienne|Tradical',
        'Rebar|fer.*aluminium|quincaillerie.*fer',
    ],
    'Peinture & Revêtement' => [
        'sous-couche|universelle.*sous',
        'peinture.*monocouche|monocouche.*blanche',
        'peinture.*anti.*rouille|anti.*rusting|silver.*paint|metallic.*paint',
    ],
    'Électricité' => [
        'câble.*trifacil|trifacil.*souple|cable.*souple',
        'interrupteur.*basique|interrupteur.*simple',
        'Schneider.*prise|bloc.*prise|2P.*T.*16A',
        'convertisseur.*500W|500W.*12V',
    ],
    'Plomberie' => [
        'té.*compression|compression.*Conex',
        'té.*cuivre.*souder|cuivre.*inégal.*souder',
        'tube.*cuivre.*barre|cuivre.*barre',
        'plomberie.*chauffage|évacuation.*eaux',
    ],
    'Fer & Aluminium' => [
        'profil.*angle.*aluminium|angle.*aluminium',
        'quincaillerie.*fer|fer.*aluminium|Rebar.*HRB400|Rebar.*460B',
    ],
    'Cuivre' => [
        'tube.*cuivre.*barre|cuivre.*barre',
        'té.*cuivre.*souder|cuivre.*inégal.*souder',
    ],
    'Quincaillerie & Fixation' => [
        'quincaillerie.*fer|fer.*aluminium',
    ],
    'Outillage' => [
        'convertisseur.*500W|500W.*12V',
    ],
];

// Récupérer toutes les catégories
$pdo = getDB();
$stmt = $pdo->query("SELECT id, nom FROM categories ORDER BY ordre ASC, id ASC");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer toutes les images disponibles
$images_disponibles = [];
if (is_dir($source_dir)) {
    $images = array_filter(scandir($source_dir), function($file) {
        return in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
    });
    $images_disponibles = array_values($images);
}

$total_images = count($images_disponibles);
$total_categories = count($categories);

if ($total_images == 0) {
    $errors[] = "Aucune image trouvée dans le dossier source : {$source_dir}";
} else {
    $success[] = "📁 {$total_images} image(s) disponible(s)";
    $success[] = "📦 {$total_categories} catégorie(s) trouvée(s)";
    
    // Mélanger les images pour distribution aléatoire
    shuffle($images_disponibles);
    
    // Traiter chaque catégorie
    foreach ($categories as $categorie) {
        $image_trouvee = null;
        $type_association = 'aléatoire';
        
        // Essayer d'abord une association intelligente
        if (isset($mapping_categories[$categorie['nom']])) {
            $patterns = $mapping_categories[$categorie['nom']];
            
            foreach ($images_disponibles as $image_file) {
                $image_name_lower = strtolower($image_file);
                
                foreach ($patterns as $pattern) {
                    if (preg_match('/' . $pattern . '/i', $image_name_lower)) {
                        $image_trouvee = $image_file;
                        $type_association = 'intelligent';
                        break 2;
                    }
                }
            }
        }
        
        // Si pas trouvé intelligemment, prendre une image aléatoire
        if (!$image_trouvee) {
            $image_trouvee = $images_disponibles[array_rand($images_disponibles)];
        }
        
        $image_path = $source_dir . $image_trouvee;
        
        // Vérifier que le fichier existe
        if (!file_exists($image_path)) {
            $errors[] = "Le fichier image n'existe pas : {$image_trouvee}";
            continue;
        }
        
        // Générer un nom unique pour l'image
        $ext = pathinfo($image_trouvee, PATHINFO_EXTENSION);
        $new_name = 'categorie_' . $categorie['id'] . '_' . uniqid() . '.' . $ext;
        $dest_path = $dest_dir . $new_name;
        
        // Copier l'image
        if (copy($image_path, $dest_path)) {
            // Mettre à jour la base de données
            $stmt = $pdo->prepare("UPDATE categories SET image = ? WHERE id = ?");
            if ($stmt->execute([$new_name, $categorie['id']])) {
                $associations[] = [
                    'image' => $image_trouvee,
                    'categorie' => $categorie['nom'],
                    'categorie_id' => $categorie['id'],
                    'new_name' => $new_name,
                    'type' => $type_association
                ];
                $icon = $type_association === 'intelligent' ? '✅' : '🎲';
                $success[] = "{$icon} Image '{$image_trouvee}' associée à la catégorie '{$categorie['nom']}' (ID: {$categorie['id']}) - {$type_association}";
            } else {
                $errors[] = "Erreur lors de la mise à jour de la base de données pour la catégorie '{$categorie['nom']}' (ID: {$categorie['id']})";
                if (file_exists($dest_path)) {
                    unlink($dest_path);
                }
            }
        } else {
            $errors[] = "Erreur lors de la copie de l'image '{$image_trouvee}' pour la catégorie '{$categorie['nom']}'";
        }
    }
    
    $total_associees = count($associations);
    if ($total_associees > 0) {
        $success[] = "🎉 {$total_associees} catégorie(s) ont maintenant une image !";
    }
}

include 'includes/header.php';
?>

<div class="admin-content">
    <h1>Association d'images aux catégories</h1>
    
    <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <h3>✅ Résultats</h3>
            <ul>
                <?php foreach ($success as $msg): ?>
                    <li><?php echo htmlspecialchars($msg); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <h3>❌ Erreurs (<?php echo count($errors); ?>)</h3>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <?php 
    $associations_intelligentes = array_filter($associations, function($a) { return $a['type'] === 'intelligent'; });
    $associations_aleatoires = array_filter($associations, function($a) { return $a['type'] === 'aléatoire'; });
    ?>
    
    <?php if (!empty($associations)): ?>
        <div class="alert alert-info" style="background: #d1ecf1; color: #0c5460; border-color: #bee5eb;">
            <h3>📊 Résumé</h3>
            <ul>
                <li><strong><?php echo count($associations_intelligentes); ?></strong> association(s) intelligente(s)</li>
                <li><strong><?php echo count($associations_aleatoires); ?></strong> association(s) aléatoire(s)</li>
                <li><strong><?php echo count($associations); ?></strong> catégorie(s) avec image au total</li>
            </ul>
        </div>
        
        <div class="table-container">
            <h3>Associations effectuées</h3>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Image originale</th>
                        <th>Catégorie associée</th>
                        <th>ID Catégorie</th>
                        <th>Type</th>
                        <th>Nom fichier</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($associations as $assoc): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($assoc['image']); ?></td>
                            <td><?php echo htmlspecialchars($assoc['categorie']); ?></td>
                            <td><?php echo $assoc['categorie_id']; ?></td>
                            <td>
                                <?php if ($assoc['type'] === 'intelligent'): ?>
                                    <span style="color: #27ae60;">✅ Intelligent</span>
                                <?php else: ?>
                                    <span style="color: #f39c12;">🎲 Aléatoire</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($assoc['new_name']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    
    <div class="admin-actions">
        <a href="categories.php" class="btn btn-primary">Retour à la liste des catégories</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

