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
import com.codename1.ui.util.Resources;

/**
 *
 * @author Pc Anis
 */
public class SidebarClt extends Form {
    private  Resources theme;

    public SidebarClt() {
        setTitle("Menu");
        setLayout(new BorderLayout());

        Container content = new Container(new BoxLayout(BoxLayout.Y_AXIS));

        Button produitsButton = new Button("Produits");
        produitsButton.addActionListener(e -> {
            new AjoutAuPanier().show();
        });
        content.add(produitsButton);

        Button panierButton = new Button("Panier");
        panierButton.addActionListener(e -> {
            new LignePaniersUI(this).show();
        });
        content.add(panierButton);
        
         Button eventsButton = new Button("Events");
        eventsButton.addActionListener(e -> {
        new ListEventsForm(this).show();
        });
        content.add(eventsButton);
        
         Button recButton = new Button("Reclamations");
        recButton.addActionListener(e -> {
           new ListReclamationForm().show();
        });
        content.add(recButton);

        Button factureButton = new Button("Factures");
        factureButton.addActionListener(e -> {
            new FacturesCltUI(this).show();
        });
        content.add(factureButton);
        
        Button LogoutButton = new Button("Se déconnecter");
        LogoutButton.addActionListener(e -> {
          new SignInForm(theme).show();
        });
        content.add(LogoutButton);

        add(BorderLayout.CENTER, content);
    }
}
