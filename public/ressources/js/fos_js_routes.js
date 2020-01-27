fos.Router.setData({"base_url":"","routes":{"agent_show":{"tokens":[["text","\/show"],["variable","\/","[^\/]++","id"],["text","\/admin\/agent"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"cart_home":{"tokens":[["text","\/admin\/cart"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"cart_home_error":{"tokens":[["variable","\/","[^\/]++","message"],["text","\/admin\/cart\/error"]],"defaults":{"message":null},"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"cart_add":{"tokens":[["variable","\/","[^\/]++","quantity"],["variable","\/","[^\/]++","id"],["text","\/admin\/cart\/add"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"cart_delete":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/admin\/cart\/delete"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"catalogue_item_registration":{"tokens":[["text","\/admin\/catalogue\/produit\/inscription"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"catalogue_item_edit":{"tokens":[["text","\/edit"],["variable","\/","[^\/]++","id"],["text","\/admin\/catalogue\/produit"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"catalogue_delete":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","id"],["text","\/admin\/catalogue\/produit"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"chat_home":{"tokens":[["text","\/admin\/chat"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"chat_agents":{"tokens":[["text","\/admin\/chat\/commerciaux"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"chat_discussion_message":{"tokens":[["text","\/messages"],["variable","\/","[^\/]++","id"],["text","\/admin\/chat\/discussion"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"chat_load_message":{"tokens":[["variable","\/","[^\/]++","nbTake"],["text","\/plus"],["variable","\/","[^\/]++","nbSkip"],["text","\/messages"],["variable","\/","[^\/]++","id"],["text","\/admin\/chat\/discussion"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"chat_discussion_register":{"tokens":[["text","\/admin\/chat\/discussion\/inscription"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"chat_discussion_edit":{"tokens":[["text","\/inscription"],["variable","\/","[^\/]++","id"],["text","\/admin\/chat\/discussion"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"chat_message_register":{"tokens":[["text","\/inscription"],["variable","\/","[^\/]++","id"],["variable","\/","[^\/]++","message"],["text","\/admin\/chat\/message"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"chat_agent_discusion":{"tokens":[["text","\/agent"],["variable","\/","[^\/]++","id"],["text","\/admin\/chat\/discussion"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"chat_add_agent":{"tokens":[["variable","\/","[^\/]++","id_discussion"],["text","\/discussion"],["variable","\/","[^\/]++","id"],["text","\/admin\/chat\/agent"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"chat_delete_discussion":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/admin\/chat\/delete\/Discussion"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"client_home":{"tokens":[["text","\/admin\/client"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"client_show":{"tokens":[["text","\/detail"],["variable","\/","[^\/]++","id"],["text","\/admin\/client"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"client_select":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/admin\/client\/selection"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"client_registration":{"tokens":[["text","\/admin\/client\/inscription"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"client_edit":{"tokens":[["text","\/edit"],["variable","\/","[^\/]++","id"],["text","\/admin\/client"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"client_address_registration":{"tokens":[["text","\/admin\/client\/address\/registration"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"client_address_edit":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/admin\/client\/address"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"client_contact_registration":{"tokens":[["text","\/contact\/inscription"],["variable","\/","[^\/]++","idClient"],["text","\/admin\/client"]],"defaults":{"idClient":null},"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"client_contact_edit":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/admin\/client\/contact"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"client_delete":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","id"],["text","\/admin\/client"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"client_contact_delete":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","id"],["text","\/admin\/client\/contact"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"order":{"tokens":[["text","\/admin\/commande\/accueil"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"order_home":{"tokens":[["text","\/admin\/commande"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"order_home_error":{"tokens":[["variable","\/","[^\/]++","message"],["text","\/admin\/commande\/error"]],"defaults":{"message":null},"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"order_quote":{"tokens":[["text","\/admin\/commande\/devis"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"order_preorder":{"tokens":[["text","\/admin\/commande\/precommande"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"order_prerefund":{"tokens":[["text","\/admin\/commande\/preavoir"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"order_refund":{"tokens":[["text","\/admin\/commande\/avoir"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"order_bill":{"tokens":[["text","\/admin\/commande\/facturation"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"order_bill_refund":{"tokens":[["text","\/admin\/commande\/facturation\/avoir"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"order_customer_valid":{"tokens":[["text","\/admin\/commande\/validation\/client"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"order_closed":{"tokens":[["text","\/admin\/commande\/cloture"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"order_refund_closed":{"tokens":[["text","\/admin\/commande\/avoir\/cloture"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"order_show":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/admin\/commande\/detail"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"order_show_quote":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/admin\/commande\/devis\/detail"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"order_show_preorder":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/admin\/commande\/precommande\/detail"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"order_show_prerefund":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/admin\/commande\/preavoir\/detail"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"order_validation":{"tokens":[["variable","\/","[^\/]++","idStatus"],["text","\/validation"],["variable","\/","[^\/]++","id"],["text","\/admin\/commande"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"order_registration":{"tokens":[["text","\/admin\/commande\/inscription"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"order_search":{"tokens":[["text","\/admin\/commande\/recherche"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"order_detail_save":{"tokens":[["text","\/detail\/sauvegarde"],["variable","\/","[^\/]++","id"],["text","\/admin\/commande"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"order_detail_save_error":{"tokens":[["variable","\/","[^\/]++","message"],["text","\/detail\/sauvegarde\/error"],["variable","\/","[^\/]++","id"],["text","\/admin\/commande"]],"defaults":{"message":null},"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"order_delivery_save":{"tokens":[["text","\/livraison\/sauvegarde"],["variable","\/","[^\/]++","id"],["text","\/admin\/commande"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"order_bill_save":{"tokens":[["text","\/facturation\/sauvegarde"],["variable","\/","[^\/]++","id"],["text","\/admin\/commande"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"order_bill_cancel":{"tokens":[["text","\/annulation"],["variable","\/","[^\/]++","id"],["text","\/admin\/commande\/facturation"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"order_pdf_bill":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/admin\/commande\/pdf\/facture"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"order_pdf_quote":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/admin\/commande\/pdf\/devis"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"order_pdf_delivery":{"tokens":[["variable","\/","[^\/]++","id"],["text","\/admin\/commande\/pdf\/bl"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"order_delete":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","id"],["text","\/admin\/commande"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"security_action":{"tokens":[["text","\/admin\/security\/action"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"security_profile":{"tokens":[["text","\/admin\/security\/profile"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"security_role":{"tokens":[["text","\/admin\/security\/role"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"security_registration":{"tokens":[["text","\/security\/agent\/inscription"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"security_edit":{"tokens":[["text","\/edit"],["variable","\/","[^\/]++","id"],["text","\/admin\/security\/agent\/inscription"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"security_activate_agent":{"tokens":[["text","\/activation"],["variable","\/","[^\/]++","id"],["text","\/admin\/security\/agent"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"security_anonymous_registration":{"tokens":[["text","\/security\/agent\/anonyme\/inscription"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"security_login":{"tokens":[["text","\/security\/agent\/connexion"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"security_logout":{"tokens":[["text","\/admin\/security\/agent\/deconnexion"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"security_delete":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","id"],["text","\/admin\/security\/agent"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"security_action_role":{"tokens":[["text","\/admin\/security\/action_role\/create"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"security_agent_profile":{"tokens":[["text","\/admin\/security\/agent_profile\/create"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"security_action_registration":{"tokens":[["text","\/admin\/security\/action\/create"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"security_action_edit":{"tokens":[["text","\/edit"],["variable","\/","[^\/]++","id"],["text","\/admin\/security\/action"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"security_action_delete":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","id"],["text","\/admin\/security\/action"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"security_role_registration":{"tokens":[["text","\/admin\/security\/role\/create"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"security_role_edit":{"tokens":[["text","\/edit"],["variable","\/","[^\/]++","id"],["text","\/admin\/security\/role"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"security_role_delete":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","id"],["text","\/admin\/security\/role"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"setting_currency":{"tokens":[["text","\/admin\/configuration\/monnaie"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"setting_tax":{"tokens":[["text","\/admin\/configuration\/taxe"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"setting_delivery_status":{"tokens":[["text","\/admin\/configuration\/facturation\/statut"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"setting_order_status":{"tokens":[["text","\/admin\/configuration\/commande\/statut"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"setting_catalogue_brand":{"tokens":[["text","\/admin\/configuration\/produit\/marque"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"setting_catalogue_group":{"tokens":[["text","\/admin\/configuration\/produit\/famille"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"setting_catalogue_provider":{"tokens":[["text","\/admin\/configuration\/produit\/fournisseur"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"setting_registration":{"tokens":[["text","\/admin\/configuration\/inscription"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"setting_edit":{"tokens":[["text","\/edit"],["variable","\/","[^\/]++","id"],["text","\/admin\/configuration"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"setting_currency_registration":{"tokens":[["text","\/admin\/configuration\/currency\/inscription"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"setting_currency_edit":{"tokens":[["text","\/edit"],["variable","\/","[^\/]++","id"],["text","\/admin\/configuration\/currency"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"setting_delivery_status_registration":{"tokens":[["text","\/admin\/configuration\/statut\/livraison\/inscription"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"setting_delivery_status_edit":{"tokens":[["text","\/edit"],["variable","\/","[^\/]++","id"],["text","\/admin\/configuration\/statut\/livraison"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"setting_tax_registration":{"tokens":[["text","\/admin\/configuration\/tax\/inscription"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"setting_tax_edit":{"tokens":[["text","\/edit"],["variable","\/","[^\/]++","id"],["text","\/admin\/configuration\/tax"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"setting_brand_registration":{"tokens":[["text","\/admin\/configuration\/marque\/inscription"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"setting_brand_edit":{"tokens":[["text","\/edit"],["variable","\/","[^\/]++","id"],["text","\/admin\/configuration\/marque"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"setting_group_registration":{"tokens":[["text","\/admin\/configuration\/famille\/inscription"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"setting_group_edit":{"tokens":[["text","\/edit"],["variable","\/","[^\/]++","id"],["text","\/admin\/configuration\/famille"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"setting_provider_registration":{"tokens":[["text","\/admin\/configuration\/fournisseur\/inscription"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"setting_provider_edit":{"tokens":[["text","\/edit"],["variable","\/","[^\/]++","id"],["text","\/admin\/configuration\/fournisseur"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"setting_order_status_registration":{"tokens":[["text","\/admin\/configuration\/commande\/statut\/inscription"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"setting_order_status_edit":{"tokens":[["text","\/edit"],["variable","\/","[^\/]++","id"],["text","\/admin\/configuration\/commande\/statut"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"setting_delete":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","id"],["text","\/admin\/configuration"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"setting_currency_delete":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","id"],["text","\/admin\/configuration\/currency"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"setting_tax_delete":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","id"],["text","\/admin\/configuration\/tax"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"setting_delivery_status_delete":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","id"],["text","\/admin\/configuration\/statut\/livraison"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"setting_provider_delete":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","id"],["text","\/admin\/configuration\/fournisseur"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"setting_group_delete":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","id"],["text","\/admin\/configuration\/famille"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"setting_brand_delete":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","id"],["text","\/admin\/configuration\/marque"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"setting_order_status_delete":{"tokens":[["text","\/delete"],["variable","\/","[^\/]++","id"],["text","\/admin\/configuration\/commande\/statut"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"statistic_order_week":{"tokens":[["text","\/admin\/statistic\/commande\/semaine"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"statistic_order_month":{"tokens":[["text","\/admin\/statistic\/commande\/mois"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"statistic_order_year":{"tokens":[["text","\/admin\/statistic\/commande\/annee"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]}},"prefix":"","host":"localhost","port":"","scheme":"http","locale":[]});