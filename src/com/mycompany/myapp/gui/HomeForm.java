/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mycompany.myapp.gui;

import com.codename1.ui.Button;
import com.codename1.ui.Form;
import com.codename1.ui.Label;
import com.codename1.ui.layouts.BoxLayout;

/**
 *
 * @author MSI
 */
public class HomeForm extends Form{
    
    public HomeForm() {
        
        setTitle("Home");
        setLayout(BoxLayout.y());
        
        add(new Label("Choose an option"));
        Button btnAddPromo = new Button("Ajouter Promo");
        Button btnListPromos = new Button("List Promos");
        Button btnListLivres = new Button("List Livres");
        Button btnAddLivre = new Button("Ajouter Livre");
        
        
        btnListPromos.addActionListener(e-> new ListPromosForm(this).show());
        btnAddPromo.addActionListener(e-> new AddPromoForm(this).show());
        btnListLivres.addActionListener(e-> new ListLivresForm(this).show());
        btnAddLivre.addActionListener(e-> new AddLivreForm(this).show());
        addAll(btnListPromos,btnListLivres,btnAddPromo ,btnAddLivre);
    }
    
}
