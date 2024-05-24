<style>
    #categories {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    #categories td, #categories th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #categories tr:nth-child(even){background-color: #f2f2f2;}

    #categories tr:hover {background-color: #ddd;}

    #categories th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #4CAF50;
        color: white;
    }
</style>

<table id="categories" width="100%">
    <thead>
    <tr>
    <td>id</td> 
    <td>Magasin</td>
    <td>Catégorie</td>
    <td>Livreur</td>
    <td>Client</td>
    <td>Produits</td>
    <td>Quantite</td> 
    <td>Date de livraison</td> 
    </tr>
    </thead> 
    <?php
     
    foreach($commandes as $c)
    {
        if($c->etat=0){
            continue;
        }
    ?>
        <tbody>
        <tr>
        <td><?php echo $c->id; ?></td> 
        <td><?php echo $c->info->receiverMagasin->name; ?></td>
        <td><?php echo $c->produit->categorie->name; ?></td>
        <td><?php echo $c->info->receiverStaff->lastname; ?> <?php echo $c->info->receiverStaff->firstname; ?></td>
        <td><?php echo $c->info->receiverClient->name; ?></td>
        <td><?php echo $c->produit->name; ?></td>   
        <td><?php echo $c->qty; ?></td>  
        <td><?php echo showDateTime($c->estimate_date, 'd M Y'); ?></td> 
    </tr>
        </tbody>
        <?php 
    }
    ?>

</table>