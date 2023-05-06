/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mycompany.myapp.gui;

import com.codename1.ui.Button;
import com.codename1.ui.Command;
import com.codename1.ui.Dialog;
import com.codename1.ui.FontImage;
import com.codename1.ui.Form;
import com.codename1.ui.Label;
import com.codename1.ui.TextArea;
import com.codename1.ui.TextField;
import com.codename1.ui.events.ActionEvent;
import com.codename1.ui.events.ActionListener;
import com.codename1.ui.layouts.BoxLayout;
import com.codename1.ui.spinner.Picker;
import com.mycompany.myapp.entities.Livre;
import com.mycompany.myapp.entities.Promo;
import com.mycompany.myapp.services.LivreService;
import com.mycompany.myapp.services.PromoService;

/**
 *
 * @author MSI
 */
public class AddLivreForm extends Form {
    
    
    public AddLivreForm(Form previous) {
        setTitle("Ajouter Livre");
        setLayout(BoxLayout.y());

        Label libelle = new Label("Libelle :");
        TextField tfLibelle = new TextField("", "Libelle");
        Label description = new Label("Description :");
        TextArea taDescription = new TextArea(2,3);
        Label categorie = new Label("Categorie :");
        TextField tfCategorie = new TextField("", "Categorie");
        Label editeur = new Label("Editeur :");
        TextField tfEditeur = new TextField("", "Editeur");
        Label langue = new Label("Langue :");
        TextField tfLangue = new TextField("", "Langue");
        Label prix = new Label("Prix :");
        TextField tfPrix = new TextField("", "Prix");

        Button btnValider = new Button("Ajouter");
  
        

        btnValider.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent evt) {
                if ((tfLibelle.getText().length()==0) )
                    Dialog.show("Alert", "Please fill all the fields", new Command("OK"));
                else
                {
                    Livre l = new Livre();
                    l.setDescription(taDescription.getText().toString());
                    l.setCategorie(tfCategorie.getText().toString());
                     if( LivreService.getInstance().addLivre(l))
                        {
                           Dialog.show("Success","Connection accepted",new Command("OK"));
                        }else
                            Dialog.show("ERROR", "Server error", new Command("OK"));
                    
                }
               new ListPromosForm(previous).showBack(); 
                
            }
            

           
        });
        
        addAll(libelle,tfLibelle,description, taDescription ,prix ,tfPrix , categorie , tfCategorie ,langue , tfLangue, btnValider);
        getToolbar().addMaterialCommandToLeftBar("", FontImage.MATERIAL_ARROW_BACK, e -> previous.showBack());

    }
}
