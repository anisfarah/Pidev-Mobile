/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mycompany.myapp.gui;

import com.codename1.ui.Button;
import com.codename1.ui.Command;
import com.codename1.ui.Dialog;
import com.codename1.ui.Display;
import com.codename1.ui.FontImage;
import com.codename1.ui.Form;
import com.codename1.ui.Label;
import com.codename1.ui.TextField;
import com.codename1.ui.events.ActionEvent;
import com.codename1.ui.events.ActionListener;
import com.codename1.ui.layouts.BoxLayout;
import com.codename1.ui.spinner.Picker;
import com.mycompany.myapp.entities.Promo;
import com.mycompany.myapp.services.PromoService;
import java.util.Date;

/**
 *
 * @author MSI
 */
public class AddPromoForm extends Form {

    public AddPromoForm(Form previous) {
        setTitle("Ajouter Promo");
        setLayout(BoxLayout.y());

        Label code = new Label("Code :");
        TextField tfCode = new TextField("", "Code");
        Label reduction = new Label("Reduction :");
        TextField tfReduction = new TextField("", "Reduction");

        Button btnValider = new Button("Ajouter Promo");

  
        

        btnValider.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent evt) {
                if ((tfCode.getText().length()==0) )
                    Dialog.show("Alert", "Please fill all the fields", new Command("OK"));
                else
                {
                    Promo p = new Promo(tfCode.getText(), Double.parseDouble(tfReduction.getText()));
                     if( PromoService.getInstance().addPromo(p))
                        {
                           Dialog.show("Success","Connection accepted",new Command("OK"));
                        }else
                            Dialog.show("ERROR", "Server error", new Command("OK"));
                    
                }
               new ListPromosForm(previous).showBack(); 
                
            }
            

           
        });
        
        addAll(code,tfCode,reduction, tfReduction , btnValider);
        getToolbar().addMaterialCommandToLeftBar("", FontImage.MATERIAL_ARROW_BACK, e -> previous.showBack());

    }

}
