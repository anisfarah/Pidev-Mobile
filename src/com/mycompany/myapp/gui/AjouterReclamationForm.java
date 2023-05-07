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
import com.mycompany.myapp.entities.Reclamation;
import com.mycompany.myapp.services.ServiceReclamation;
import java.util.Date;

/**
 *
 * @author Dell 6540
 */
    
public class AjouterReclamationForm extends Form {
    
    
    public AjouterReclamationForm(Form previous) {
        setTitle("Ajouter Livre");
        setLayout(BoxLayout.y());

        Label Contenu = new Label("Contenu :");
        TextArea txtcontenu = new TextArea(2,3);

        Button btnValider = new Button("Ajouter");
  
        

        btnValider.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent evt) {
                if ((txtcontenu.getText().length()==0) )
                    Dialog.show("Alert", "Please fill all the fields", new Command("OK"));
                else
                {
                    Reclamation r = new Reclamation();
                    r.setContenu(txtcontenu.getText());
                    Date currentDate = new Date();
                    r.setDateRec(currentDate);
                    r.setIdUser(1);
                    r.setIdType(1);
                    r.setEtat("En cours");
                    r.setImg("hhh");

//                    r.setDateRec(txtDate.getText().toString());
                     if( ServiceReclamation.getInstance().addReclamation(r))
                        {
                           Dialog.show("Success","Reclamation Ajoutée",new Command("OK"));
                           new ListReclamationForm().show();
                           
                        }else
                            Dialog.show("ERROR", "Server error", new Command("OK"));
                    
                }
                
            }
            

           
        });
        
        addAll(Contenu,txtcontenu,btnValider);
        getToolbar().addMaterialCommandToLeftBar("", FontImage.MATERIAL_ARROW_BACK, e -> previous.showBack());

    }
}
