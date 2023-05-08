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
import com.mycompany.myapp.entities.Promo;
import com.mycompany.myapp.entities.Theme;
import com.mycompany.myapp.services.PromoService;
import com.mycompany.myapp.services.ServiceTheme;
import java.util.ArrayList;

/**
 *
 * @author Pc Anis
 */
public class ListThemesForm extends Form {
     public ListThemesForm(Form previous) {
        setTitle("List Themes");
        setLayout(BoxLayout.y());

        Label label = new Label("Liste des themes :");
        add(label);
        ArrayList<Theme> themes = ServiceTheme.getInstance().getAllThemes();

        for (Theme t : themes) {
            addElement(t);
        }

        getToolbar().addMaterialCommandToLeftBar("", FontImage.MATERIAL_ARROW_BACK, e -> previous.showBack());
//                    getToolbar().addMaterialCommandToRightBar("", FontImage.MATERIAL_ADD, e -> new AddPromoForm(this).show());


    }

    public void addElement(Theme theme) {
        ServiceTheme ps = new ServiceTheme();
    

        Label nom = new Label("Nom : " + theme.getNom());
        Label description = new Label("Description : " + theme.getDescription());
        
        Button detail = new Button("Détails");
        detail.addActionListener(e -> {
            Dialog.show("Détails", "Nom :"+theme.getNom()+"\nDescription : "+ theme.getDescription(), "OK", null);
        });
        
        Button supprimer =new Button("Supprimer");
            supprimer.addActionListener(e -> {
               Dialog alert = new Dialog("Confirmation");
                SpanLabel message = new SpanLabel("Etes-vous sur de vouloir supprimer ce theme?");
                alert.add(message);
                Button ok = new Button("Confirmer");
                Button cancel = new Button(new Command("Annuler"));
                //User clicks on ok to delete account
                ok.addActionListener((ActionListener) (ActionEvent evt) -> {
                    ps.deleteTheme(theme.getIdtheme());
                   
                    alert.dispose();
                    refreshTheme();
                     
               });
                alert.add(cancel);
                alert.add(ok);
                alert.showDialog();
                new ListThemesForm(this).show();
                
                
                
                
               
             });
            
            
//         Button modifier = new Button("Modifier");
            //modifier.addActionListener(e-> new EditThemeForm(this,theme).show());
        

        
      
        addAll(nom,description,detail,supprimer);
        
       

    }
    
}
