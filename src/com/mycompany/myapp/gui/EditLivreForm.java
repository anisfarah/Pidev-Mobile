/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mycompany.myapp.gui;

import com.codename1.ui.Button;
import com.codename1.ui.Command;
import com.codename1.ui.Dialog;
import com.codename1.ui.Form;
import com.codename1.ui.Label;
import com.codename1.ui.TextArea;
import com.codename1.ui.TextField;
import com.codename1.ui.layouts.BoxLayout;
import com.mycompany.myapp.entities.Livre;
import com.mycompany.myapp.services.LivreService;

/**
 *
 * @author Pc Anis
 */
public class EditLivreForm extends Form {
     private Livre livre; // L'objet Promo à modifier

    
    

    public EditLivreForm(Form previous, Livre livre) {
        setTitle("Modifier Livre");
        setLayout(BoxLayout.y());
        this.livre = livre;
        Label libelle = new Label("Libelle :");
        TextField tfLibelle = new TextField(livre.getLibelle(), "Libelle");
        Label description = new Label("Description :");
        TextArea taDescription = new TextArea(livre.getDescription());
        Label categorie = new Label("Categorie :");
        TextField tfCategorie = new TextField(livre.getCategorie(), "Categorie");
        Label editeur = new Label("Editeur :");
        TextField tfEditeur = new TextField(livre.getEditeur(), "Editeur");
        Label langue = new Label("Langue :");
        TextField tfLangue = new TextField(livre.getLangue(), "Langue");
        Label prix = new Label("Prix :");
        TextField tfPrix = new TextField(Float.toString(livre.getPrix()), "Prix");

     

  

        // Ajouter les champs au formulaire
       addAll(libelle,tfLibelle,description, taDescription ,prix ,tfPrix , categorie , tfCategorie ,langue , tfLangue, editeur , tfEditeur );

        // Ajouter un bouton pour enregistrer les modifications
        Button modifier = new Button("Modifier");
        modifier.addActionListener(e -> {
            // Mettre à jour les données de la promo
            livre.setLibelle(tfLibelle.getText());
            livre.setDescription(taDescription.getText());
            livre.setCategorie(tfCategorie.getText());
            livre.setEditeur(tfEditeur.getText());
            livre.setLangue(tfLangue.getText());
            livre.setPrix(Float.parseFloat(tfPrix.getText()));
            

            // Enregistrer les modifications dans la base de données
            LivreService.getInstance().updateLivre(livre);
            Dialog.show("Success","Livre modifié avec succés",new Command("OK"));
            
            
            new ListLivresForm(previous).showBack();
        });
        add(modifier);

        // Ajouter un bouton pour annuler les modifications
        Button cancelButton = new Button("Annuler");
        cancelButton.addActionListener(e -> new ListLivresForm(previous).showBack());
        add(cancelButton);
    }
    
}
