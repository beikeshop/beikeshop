<?php

/**
 * order.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-02 14:22:41
 * @modified   2022-08-02 14:22:41
 */

return [
    // Titre
    'heading_title' => 'Guide du débutant',

    //Languette
    'tab_basic'            => 'Paramètres de base',
    'tab_lingual'          => 'Plusieurs langues et devises',
    'tab_product'          => 'Créer un produit',
    'tab_theme'            => 'Décoration de boutique',
    'tab_payment_shipping' => 'Paiement et logistique',
    'tab_mail'             => 'Courrier de configuration',

    //Texte
    'text_extension'  => 'Extension',
    'text_success'    => 'Succès : Le guide du débutant a été modifié ! ',
    'text_edit'       => 'Guide d\'édition pour les débutants',
    'text_view'       => 'Afficher les détails...',
    'text_greeting'   => 'Félicitations, votre site Web a installé BeikeShop avec succès ! ',
    'text_greeting_1' => 'Nous vous guiderons pour effectuer quelques configurations personnalisées de base sur le système afin de vous aider à comprendre les fonctions du système BeikeShop et à commencer à l\'utiliser rapidement ! ',
    'text_basic_1'    => 'Tout d\'abord, vous pouvez configurer les informations importantes suivantes dans les paramètres système :',
    'text_lingual_1'  => 'Le système BeikeShop prend en charge plusieurs langues et devises. Avant de commencer à créer votre premier produit, vous pouvez sélectionner la langue et la devise par défaut à la réception du centre commercial ! ',
    'text_lingual_2'  => 'Si vous n\'avez besoin d\'utiliser qu\'une seule langue et devise, vous pouvez supprimer les autres langues et devises. Évitez les tracas liés à la saisie d\'informations dans plusieurs langues lors de la création de produits. ',
    'text_product_1'  => 'Pendant l\'installation du système, certaines données de produit par défaut seront automatiquement importées à des fins de démonstration. Vous pouvez d\'abord essayer de <a href="' . admin_route('products.create') . '">Créer des produits</a> ! ',
    'text_product_2'  => 'BeikeShop offre de puissantes capacités de gestion de produits ! Y compris : <a href="' . admin_route('categories.index') . '">Classification des produits</a>, <a href="' . admin_route('brands.index') . '">Gestion de la marque</a>, produits multi-spécifications, <a href="' . admin_route('multi_filter.index') . '">Filtrage avancé</a>, <a href="' . admin_route('attributes.index') . '">Attributs du produit</a> et autres fonctions. ',
    'text_theme_1'    => 'Le système dispose d\'un ensemble de modèles de thème par défaut installés par défaut. Si le thème par défaut ne répond pas à vos besoins, vous pouvez également utiliser <a href="' . admin_route('marketing.index', [' type' => ' theme']) . '">Plugin Market</a> pour acheter d\'autres thèmes de modèles. ',
    'text_theme_2'    => 'De plus, la page d\'accueil du modèle de thème frontal est présentée par le module via la mise en page. Vous devrez peut-être ajuster certains paramètres du module via la mise en page. ',
    'text_theme_3'    => 'Si vous achetez l\'APP, nous fournissons également une fonction spécifiquement pour la <a href="' . admin_route('design_app_home.index') . '">Conception de la page d\'accueil de l\'APP</a>. ',
    'text_payment_1'  => 'BeikeShop fournit des canaux de paiement à l\'étranger couramment utilisés, tels que PayPal, Stripe, etc. Avant de passer officiellement des commandes, vous devez activer et configurer le mode de paiement correspondant. ',
    'text_payment_2'  => 'Remarque : L\'examen de certaines applications d\'interface de paiement prend plus de temps, veuillez postuler à l\'avance. Les méthodes de paiement utilisées en Chine peuvent nécessiter l\'enregistrement d\'un nom de domaine de site Web. ',
    'text_payment_3'  => 'De plus, vous devez également définir le mode de livraison logistique que les clients pourront choisir. Le système fournit gratuitement un plug-in de frais d’expédition fixes. ',
    'text_payment_4'  => 'Vous pouvez également accéder à BeikeShop<a href="' . admin_route('marketing.index') . '">"Plug-in Market"</a> pour découvrir et télécharger davantage de méthodes de paiement et de logistique. méthodes ! ',
    'text_mail_1'     => 'Les notifications par e-mail peuvent tenir vos clients informés de l\'état de leur commande, et ils peuvent également s\'inscrire et récupérer des mots de passe par e-mail. Vous pouvez configurer SMTP en fonction des besoins réels de votre entreprise, et des moteurs de messagerie tels que SendCloud sont utilisés pour envoyer des e-mails. ',
    'text_mail_2'     => 'Rappel chaleureux : l\'envoi fréquent d\'e-mails peut entraîner le marquage de vos e-mails comme spam. Nous vous recommandons d\'utiliser SendCloud (service payant) pour envoyer des e-mails. ',

    // Bouton
    'button_setting_general' => 'Paramètres de base du site Web',
    'button_setting_store'   => 'Nom du site Web',
    'button_setting_logo'    => 'Changer le logo',
    'button_setting_option'  => 'Paramètre des options',
    'button_setting'         => 'Tous les paramètres système',
    'button_langue'          => 'Gestion des langues',
    'button_currency'        => 'Gestion des devises',
    'button_product'         => 'Afficher le produit',
    'button_product_create'  => 'Créer un produit',
    'button_theme_pc'        => 'Paramètres du modèle',
    'button_theme_h5'        => 'Paramètres du thème mobile',
    'button_theme'           => 'Tous les thèmes',
    'button_layout'          => 'Gestion de la mise en page',
    'button_payment'         => 'Mode de paiement',
    'button_shipping'        => 'Mode d\'expédition',
    'button_mail'            => 'Paramètres de messagerie',
    'button_sms'             => 'Configuration SMS',
    'button_hide'            => 'Ne plus afficher',
];
