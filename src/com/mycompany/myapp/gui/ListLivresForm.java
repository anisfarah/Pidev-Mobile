/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mycompany.myapp.gui;

import com.codename1.components.SpanLabel;
import com.codename1.ui.Button;
import com.codename1.ui.Command;
import com.codename1.ui.Dialog;
import com.codename1.ui.FontImage;
import com.codename1.ui.Form;
import com.codename1.ui.Label;
import com.codename1.ui.events.ActionEvent;
import com.codename1.ui.events.ActionListener;
import com.codename1.ui.layouts.BoxLayout;
import com.mycompany.myapp.entities.Livre;
import com.mycompany.myapp.services.LivreService;
import java.util.ArrayList;

/**
 *
 * @author MSI
 */
public class ListLivresForm extends Form {

    public ListLivresForm(Form previous) {
        setTitle("List Livres");
        setLayout(BoxLayout.y());

        /*SpanLabel sp = new SpanLabel();
        sp.setText(ServiceTask.getInstance().getAllTasks().toString());
        add(sp);
         */
        Label label = new Label("Liste des Livres :");

        add(label);
        ArrayList<Livre> livres = LivreService.getInstance().getAllLivres();

        for (Livre l : livres) {
            addElement(l);
        }

        getToolbar().addMaterialCommandToLeftBar("", FontImage.MATERIAL_ARROW_BACK, e -> previous.showBack());
            getToolbar().addMaterialCommandToRightBar("", FontImage.MATERIAL_ADD, e -> new AddLivreForm(this).show());


    }

    public void addElement(Livre livre) {
        
        LivreService ls = new LivreService();

        Label libelle = new Label("Libelle : " + livre.getLibelle());
        Label prix = new Label("Prix : " + livre.getPrix());

        Button detail = new Button("Détails");
        detail.addActionListener(e -> {
            Dialog.show("Détails", "Libelle :" + livre.getLibelle()+ "\nPrix : " + livre.getPrix()
                    + "\nDescription :" + livre.getDescription()
                    + "\nCatégorie :" + livre.getCategorie()+ "\nEditeur :" + livre.getEditeur()
                    , "OK", null);
        });
        
         Button supprimer =new Button("Supprimer");
            supprimer.addActionListener(e -> {
               Dialog alert = new Dialog("Confirmation");
                SpanLabel message = new SpanLabel("Etes-vous sur de vouloir supprimer cet livre?");
                alert.add(message);
                Button ok = new Button("Confirmer");
                Button cancel = new Button(new Command("Annuler"));
                //User clicks on ok to delete account
                ok.addActionListener((ActionListener) (ActionEvent evt) -> {
                    ls.deleteLivre(livre.getId());
                    
                    alert.dispose();
                    refreshTheme();
               });
                alert.add(cancel);
                alert.add(ok);
                alert.showDialog();
                new ListLivresForm(this).show();
                
                
               
             });
             Button modifier = new Button("Modifier");
            modifier.addActionListener(e-> new EditLivreForm(this,livre).show());

        addAll(libelle,prix,detail,supprimer , modifier);
        

    }

}