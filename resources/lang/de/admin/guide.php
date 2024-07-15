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
    // Überschrift
    'heading_title' => 'Anleitung für Neulinge',

    //Tab
    'tab_basic'         => 'Grundeinstellungen',
    'tab_lingual'       => 'Mehrere Sprachen und Währungen',
    'tab_product'       => 'Produkt erstellen',
    'tab_theme'         => 'Shop-Dekoration',
    'tab_paid_shipping' => 'Zahlung und Logistik',
    'tab_mail'          => 'Konfigurationsmail',

    //Text
    'text_extension'  => 'Erweiterung',
    'text_success'    => 'Erfolg: Der Leitfaden für Anfänger wurde geändert! ',
    'text_edit'       => 'Leitfaden zum Bearbeiten für Neulinge',
    'text_view'       => 'Details anzeigen...',
    'text_greeting'   => 'Herzlichen Glückwunsch, Ihre Website hat BeikeShop erfolgreich installiert! ',
    'text_greeting_1' => 'Wir werden Sie dabei unterstützen, einige grundlegende benutzerdefinierte Konfigurationen am System vorzunehmen, damit Sie die Funktionen des BeikeShop-Systems verstehen und schnell mit der Nutzung beginnen können! ',
    'text_basic_1'    => 'Zunächst können Sie in den Systemeinstellungen folgende wichtige Informationen konfigurieren:',
    'text_sprache_1'  => 'Das BeikeShop-System unterstützt mehrere Sprachen und Währungen. Bevor Sie mit der Erstellung Ihres ersten Produkts beginnen, können Sie an der Rezeption des Einkaufszentrums die Standardsprache und -währung auswählen! ',
    'text_sprache_2'  => 'Wenn Sie nur eine Sprache und Währung verwenden müssen, können Sie die anderen Sprachen und Währungen löschen. Vermeiden Sie beim Erstellen von Produkten die mühsame Eingabe von Informationen in mehreren Sprachen. ',
    'text_product_1'  => 'Während der Systeminstallation werden einige Standardproduktdaten automatisch zu Demonstrationszwecken importiert. Sie können <a href="' . admin_route('products.create') . '">Erstellen Sie zuerst Produkte</a> ausprobieren! ',
    'text_product_2'  => 'BeikeShop bietet leistungsstarke Produktmanagementfunktionen! Einschließlich: <a href="' . admin_route('categories.index') . '">Produktklassifizierung</a>, <a href="' . admin_route('brands.index') . '">Markenmanagement</a>, Produkte mit mehreren Spezifikationen, <a href="' . admin_route('multi_filter.index') . '">Erweiterte Filterung</a>, <a href="' . admin_route('attributes.index') . '">Produktattribute</a> und andere Funktionen. ',
    'text_theme_1'    => 'Das System verfügt standardmäßig über eine Reihe von Standard-Theme-Vorlagen. Wenn das Standard-Theme nicht Ihren Anforderungen entspricht, können Sie auch <a href="' . admin_route('marketing.index', [' type' => ' theme']) . '">Plugin Market</a>, um andere Vorlagenthemen zu erwerben. ',
    'text_theme_2'    => 'Außerdem wird die Homepage der Front-End-Theme-Vorlage vom Modul über das Layout dargestellt. Möglicherweise müssen Sie einige Moduleinstellungen über das Layout anpassen. ',
    'text_theme_3'    => 'Wenn Sie die APP kaufen, stellen wir auch eine Funktion speziell für <a href="' . admin_route('design_app_home.index') . '">APP-Homepage-Design</a> zur Verfügung. ',
    'text_zahlung_1'  => 'BeikeShop bietet häufig verwendete Zahlungskanäle im Ausland, wie z. B. das Standard-PayPal, Stripe usw. Bevor Sie offiziell eine Bestellung aufgeben, müssen Sie die entsprechende Zahlungsmethode aktivieren und konfigurieren. ',
    'text_zahlung_2'  => 'Hinweis: Die Prüfung einiger Anträge für Zahlungsschnittstellen dauert länger. Bitte bewerben Sie sich im Voraus. In China verwendete Zahlungsmethoden erfordern möglicherweise die Registrierung eines Website-Domainnamens. ',
    'text_zahlung_3'  => 'Darüber hinaus müssen Sie auch die Logistik-Liefermethode festlegen, die Kunden auswählen können. Das System bietet ein kostenloses Plug-in mit festen Versandkosten. ',
    'text_zahlung_4'  => 'Sie können auch zu BeikeShop<a href="' . admin_route('marketing.index') . '">"Plug-in Market"</a> gehen, um mehr Zahlungsmethoden und Logistik zu erfahren und herunterzuladen Methoden! ',
    'text_mail_1'     => 'E-Mail-Benachrichtigungen können Ihre Kunden über den Bestellstatus auf dem Laufenden halten und sie können sich auch per E-Mail registrieren und Passwörter abrufen. Sie können SMTP entsprechend den tatsächlichen Geschäftsanforderungen konfigurieren und zum Versenden von E-Mails werden E-Mail-Engines wie SendCloud verwendet. ',
    'text_mail_2'     => 'Warme Erinnerung: Häufiges Versenden von E-Mails kann dazu führen, dass Ihre E-Mails als Spam markiert werden. Wir empfehlen die Verwendung von SendCloud (kostenpflichtiger Dienst) zum Versenden von E-Mails. ',

    // Taste
    'button_setting_general' => 'Grundeinstellungen der Website',
    'button_setting_store'   => 'Website-Name',
    'button_setting_logo'    => 'Logo ändern',
    'button_setting_option'  => 'Optionseinstellung',
    'button_setting'         => 'Alle Systemeinstellungen',
    'button_lingual'         => 'Sprachverwaltung',
    'button_currency'        => 'Währungsverwaltung',
    'button_product'         => 'Produkt anzeigen',
    'button_product_create'  => 'Produkt erstellen',
    'button_theme_pc'        => 'Vorlageneinstellungen',
    'button_theme_h5'        => 'Mobile Theme-Einstellungen',
    'button_theme'           => 'Alle Themen',
    'button_layout'          => 'Layoutverwaltung',
    'button_zahlung'         => 'Zahlungsmethode',
    'button_shipping'        => 'Versandart',
    'button_mail'            => 'Mail-Einstellungen',
    'button_sms'             => 'SMS-Konfiguration',
    'button_hide'            => 'Nicht mehr anzeigen',
];
