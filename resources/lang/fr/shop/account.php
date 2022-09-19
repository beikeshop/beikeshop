<?php
/**
 * account.php
 *
 * @copyright  2022 beikeshop.com - All Rights Reserved
 * @link       https://beikeshop.com
 * @author     Edward Yang <yangjin@guangda.work>
 * @created    2022-08-04 10:59:15
 * @modified   2022-08-04 10:59:15
 */

return [
    'index' => 'centre personnel',
    'revise_info' => 'Modifier les informations',
    'collect' => 'recueillir',
    'coupon' => 'coupon',
    'my_order' => 'Ma commande',
    'orders' => 'Tous les ordres',
    'pending_payment' => 'En attente de paiement',
    'pending_send' => 'être délivré',
    'pending_receipt' => 'réception en attente',
    'after_sales' => 'Après vente',
    'no_order' => 'Vous n\'avez pas encore de commande!',
    'to_buy' => 'passer une commande',
    'order_number' => 'numéro de commande',
    'order_time' => 'temps de commande',
    'state' => 'Etat',
    'amount' => 'montant',
    'check_details' => 'vérifier les détails',
    'all' => 'commun',
    'items' => 'Articles',
    'verify_code_expired' => 'Votre code de vérification a expiré (10minutes), veuillez le récupérer à nouveau',
    'verify_code_error' => 'Votre code de vérification est erroné',
    'account_not_exist' => 'Le compte n\'existe pas',

    'edit' => [
        'index' => 'Modifier les informations personnelles',
        'modify_avatar' => 'Modifier l\'avatar',
        'suggest' => 'Téléchargez une image JPG ou PNG. 300 x 300 est recommandé.',
        'name' => 'Nom',
        'email' => 'Courrier',
        'crop' => 'recadrer',
        'password_edit_success' => 'Réinitialisation du mot de passe terminée',
        'origin_password_fail' => 'Le mot de passe d\'origine est erroné',
    ],

    'wishlist' => [
        'index' => 'ma collection',
        'product' => 'produit de base',
        'price' => 'le prix',
        'check_details' => 'vérifier les détails',
    ],

    'order' => [
        'index' => 'Ma commande',
        'completed' => 'Réception confirmée',
        'cancelled' => 'commande annulée',
        'order_details' => 'détails de la commande',
        'amount' => 'montant',
        'state' => 'Etat',
        'order_number' => 'numéro de commande',
        'check' => 'Vérifier',

        'order_info' => [
            'index' => 'détails de la commande',
            'order_details' => 'détails de la commande',
            'to_pay' => 'payer',
            'cancel' => 'annuler la commande',
            'confirm_receipt' => 'confirmer la réception des marchandises',
            'order_number' => 'numéro de commande',
            'order_date' => 'date de commande',
            'state' => 'Etat',
            'order_amount' => 'montant de la commande',
            'order_items' => 'Items commandés',
            'apply_after_sales' => 'Faire une demande d\'après-vente',
            'order_total' => 'commande totale',
            'logistics_status' => 'Statut logistique',
            'order_status' => 'Statut de la commande',
            'remark' => 'Remarque',
            'update_time' => 'temps de mise à jour',
        ],

        'order_success' => [
            'order_success' => 'Félicitations, la commande a été générée avec succès!',
            'order_number' => 'numéro de commande',
            'amounts_payable' => 'Montants à payer ',
            'payment_method' => 'mode de paiement ',
            'view_order' => 'Voir d\'autres détails ',
            'pay_now' => 'payer immédiatement ',
            'kind_tips' => 'Rappel: Votre commande a été générée avec succès, veuillez effectuer le paiement dès que possible~ ',
            'also' => 'Vous pouvez également',
            'continue_purchase' => 'continuer à acheter',
            'contact_customer_service' => 'Si vous avez des questions pendant le processus de commande, vous pouvez contacter notre service clientèle à tout moment',
            'emaill' => 'Courrier',
            'service_hotline' => 'Service d\'assistance téléphonique',
        ],

    ],

    'addresses' => [
        'index' => 'mon adresse',
        'add_address' => 'Ajouter une nouvelle adresse',
        'default_address' => 'Adresse par défaut',
        'delete' => 'effacer',
        'edit' => 'Éditer',
        'enter_name' => 'Veuillez saisir votre nom',
        'enter_phone' => 'Veuillez saisir votre numéro de téléphone',
        'enter_address' => 'Veuillez entrer l\'adresse détaillée 1',
        'select_province' => 'Veuillez sélectionner la province',
        'enter_city' => 'Merci de renseigner la ville',
        'confirm_delete' => 'Voulez-vous vraiment supprimer l\'adresse?',
        'hint' => 'indice',
        'check_form' => 'Veuillez vérifier que le formulaire est correctement rempli',
    ],

    'rma' => [
        'index' => 'mon après vente',
        'commodity' => 'produit de base',
        'quantity' => 'quantité',
        'service_type' => 'Type de service',
        'return_reason' => 'Raison du retour',
        'creation_time' => 'temps de creation',
        'check' => 'Vérifier',

        'rma_info' => [
            'index' => 'Détails après-vente',
        ],

        'rma_form' => [
            'index' => 'Soumettre des informations après-vente',
            'service_type' => 'Type de service',
            'return_quantity' => 'Quantité retournée',
            'unpacked' => 'déballé',
            'return_reason' => 'Raison du retour',
            'remark' => 'Remarque',
        ]
    ]
];
