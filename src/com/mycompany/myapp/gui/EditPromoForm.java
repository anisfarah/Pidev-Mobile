/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mycompany.myapp.gui;

import com.codename1.ui.Button;
import com.codename1.ui.Form;
import com.codename1.ui.TextField;
import com.codename1.ui.layouts.BoxLayout;
import com.codename1.ui.spinner.Picker;
import com.mycompany.myapp.entities.Promo;
import com.mycompany.myapp.services.PromoService;


/**
 *
 * @author MSI
 */
public class EditPromoForm  extends Form{
    private Promo promo; // L'objet Promo à modifier

    private TextField tfCode;
    private TextField tfReduction;
    private Picker dateDebutPicker;
    private Picker dateFinPicker;

    public EditPromoForm(Form previous, Promo promo) {
        setTitle("Modifier Promo");
        setLayout(BoxLayout.y());

        this.promo = promo;

        // Créer les champs de saisie et les remplir avec les données existantes
        tfCode = new TextField(promo.getCode(), "Code");
        tfReduction = new TextField(Double.toString(promo.getReduction()), "Réduction");
        dateDebutPicker = new Picker();
        //dateDebutPicker.setDate(promo.getDate_debut().);
        dateFinPicker = new Picker();
        //ateFinPicker.setDate(promo.getDate_fin().toLocalDate());

        // Ajouter les champs au formulaire
        addAll(tfCode, tfReduction, dateDebutPicker, dateFinPicker);

        // Ajouter un bouton pour enregistrer les modifications
        Button modifier = new Button("Modifier");
        modifier.addActionListener(e -> {
            // Mettre à jour les données de la promo
            promo.setCode(tfCode.getText());
            promo.setReduction(Double.parseDouble(tfReduction.getText()));
            //promo.setDate_debut(Timestamp.valueOf(dateDebutPicker.getDate().atStartOfDay()));
            //promo.setDate_fin(Timestamp.valueOf(dateFinPicker.getDate().atStartOfDay()));

            // Enregistrer les modifications dans la base de données
            PromoService.getInstance().updatePromo(promo);

            // Retourner à la liste des promos
            new ListPromosForm(previous).showBack();
        });
        add(modifier);

        // Ajouter un bouton pour annuler les modifications
        Button cancelButton = new Button("Annuler");
        cancelButton.addActionListener(e -> new ListPromosForm(previous).showBack());
        add(cancelButton);
    }
    
}
