///*
// * To change this license header, choose License Headers in Project Properties.
// * To change this template file, choose Tools | Templates
// * and open the template in the editor.
// */
//package com.mycompany.myapp.gui;
//
//import com.codename1.ui.Button;
//import com.codename1.ui.Command;
//import com.codename1.ui.Dialog;
//import com.codename1.ui.FontImage;
//import com.codename1.ui.Form;
//import com.codename1.ui.Label;
//import com.codename1.ui.TextArea;
//import com.codename1.ui.TextField;
//import com.codename1.ui.events.ActionEvent;
//import com.codename1.ui.events.ActionListener;
//import com.codename1.ui.layouts.BoxLayout;
//import com.mycompany.myapp.entities.Event;
//import com.mycompany.myapp.services.ServiceEvent;
//
///**
// *
// * @author Pc Anis
// */
//public class AddEventForm extends Form {
//    public AddEventForm(Form previous) {
//        setTitle("Ajouter Promo");
//        setLayout(BoxLayout.y());
//
//        Label nom = new Label("Nom :");
//        TextField tfNom = new TextField("", "Nom");
//        Label description = new Label("Reduction :");
//        TextArea taDescription = new TextArea(2,3);
//        Label lieu = new Label("Lieu :");
//        TextField tfLieu = new TextField("", "Lieu");
//        Label prix = new Label("Prix :");
//        TextField tfPrix = new TextField("", "Prix");
//
//        Button btnValider = new Button("Ajouter Event");
//
//  
//        
//
//        btnValider.addActionListener(new ActionListener() {
//            @Override
//            public void actionPerformed(ActionEvent evt) {
//                if ((tfNom.getText().length()==0) )
//                    Dialog.show("Alert", "Please fill all the fields", new Command("OK"));
//                else
//                {
//                    Event e = new Event(tfNom.getText().toString(), taDescription.getText(),tfLieu.getText(),Float.parseFloat(tfPrix.getText()));
//                     if( ServiceEvent.getInstance().addEvent(e))
//                        {
//                           Dialog.show("Success","Connection accepted",new Command("OK"));
//                        }else
//                            Dialog.show("ERROR", "Server error", new Command("OK"));
//                    
//                }
//               new ListEventsAdminForm(previous).showBack(); 
//                
//            }
//
//            @Override
//            public void actionPerformed(ActionEvent evt) {
//                throw new UnsupportedOperationException("Not supported yet."); //To change body of generated methods, choose Tools | Templates.
//            }
//            
//
//           
//        });
//        
//        addAll(nom,tfNom,description, taDescription ,lieu ,tfLieu , prix , tfPrix , btnValider);
//        getToolbar().addMaterialCommandToLeftBar("", FontImage.MATERIAL_ARROW_BACK, e -> previous.showBack());
//
//    }
//
//}
