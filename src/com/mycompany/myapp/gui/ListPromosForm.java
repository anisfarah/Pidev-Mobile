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
import com.mycompany.myapp.services.PromoService;
import java.util.ArrayList;

/**
 *
 * @author MSI
 */
public class ListPromosForm extends Form {

    public ListPromosForm(Form previous) {
        setTitle("List Promos");
        setLayout(BoxLayout.y());

        Label label = new Label("Liste des promos :");
        add(label);
        ArrayList<Promo> promos = PromoService.getInstance().getAllPromos();

        for (Promo p : promos) {
            addElement(p);
        }

        getToolbar().addMaterialCommandToLeftBar("", FontImage.MATERIAL_ARROW_BACK, e -> previous.showBack());
                    getToolbar().addMaterialCommandToRightBar("", FontImage.MATERIAL_ADD, e -> new AddPromoForm(this).show());


    }

    public void addElement(Promo promo) {
        PromoService ps = new PromoService();
    

        Label code = new Label("Code : " + promo.getCode());
        Label reduction = new Label("Reduction : " + promo.getReduction());
        
        Button detail = new Button("Détails");
        detail.addActionListener(e -> {
            Dialog.show("Détails", "Code :"+promo.getCode()+"\nReduction : "+ promo.getReduction()
                    +"\nDateDebut :"+promo.getDate_debut().toString()
                    +"\nDateFin :"+promo.getDate_fin().toString(), "OK", null);
        });
        
        Button supprimer =new Button("Supprimer");
            supprimer.addActionListener(e -> {
               Dialog alert = new Dialog("Confirmation");
                SpanLabel message = new SpanLabel("Etes-vous sur de vouloir supprimer cette promo?");
                alert.add(message);
                Button ok = new Button("Confirmer");
                Button cancel = new Button(new Command("Annuler"));
                //User clicks on ok to delete account
                ok.addActionListener((ActionListener) (ActionEvent evt) -> {
                    ps.deletePromo(promo.getId());
                   
                    alert.dispose();
                    refreshTheme();
                     
               });
                alert.add(cancel);
                alert.add(ok);
                alert.showDialog();
                
                
                
                
               
             });
            
            
         Button modifier = new Button("Modifier");
            modifier.addActionListener(e-> new EditPromoForm(this,promo).show());
        

        
      
        addAll(code,reduction,detail,modifier,supprimer);
        
       

    }
    

}