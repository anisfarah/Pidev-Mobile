/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mycompany.myapp.gui;

import com.codename1.ui.Button;
import com.codename1.ui.Container;
import com.codename1.ui.Form;
import com.codename1.ui.layouts.BorderLayout;
import com.codename1.ui.layouts.BoxLayout;

/**
 *
 * @author Pc Anis
 */
public class SidebarAdmin extends Form{

    public SidebarAdmin() {
        setTitle("Menu Admin");
        setLayout(new BorderLayout());

        Container content = new Container(new BoxLayout(BoxLayout.Y_AXIS));

        Button produitsButton = new Button("Utilisateurs");
        produitsButton.addActionListener(e -> {
        });
        content.add(produitsButton);

        Button panierButton = new Button("Livres");
        panierButton.addActionListener(e -> {
            new ListLivresForm(this).show();
        });
        content.add(panierButton);
        
         Button eventsButton = new Button("Events");
        eventsButton.addActionListener(e -> {
        });
        content.add(eventsButton);
        
         Button recButton = new Button("Reclamations");
        recButton.addActionListener(e -> {
        });
        content.add(recButton);

         Button promoButton = new Button("Promos");
        promoButton.addActionListener(e -> {
            new ListPromosForm(this).show();
        });
        content.add(promoButton);
        
        Button factureButton = new Button("Factures");
        factureButton.addActionListener(e -> {
        });
        content.add(factureButton);

        add(BorderLayout.CENTER, content);
    }
    
}
