/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mycompany.myapp.gui;

import com.codename1.components.ImageViewer;
import com.codename1.ui.Button;
import com.codename1.ui.Container;
import com.codename1.ui.Display;
import com.codename1.ui.EncodedImage;
import com.codename1.ui.FontImage;
import com.codename1.ui.Form;
import com.codename1.ui.Label;
import com.codename1.ui.Image;
import com.codename1.ui.Toolbar;
import com.codename1.ui.URLImage;
import com.codename1.ui.layouts.BorderLayout;
import com.codename1.ui.layouts.BoxLayout;
import com.codename1.ui.plaf.UIManager;
import com.codename1.ui.util.Resources;
import com.mycompany.myapp.entities.Livre;
import com.mycompany.myapp.services.ServicePanier;
import java.io.IOException;
import java.util.ArrayList;

/**
 *
 * @author Pc Anis
 */
public class AjoutAuPanier extends Form {
        Form current;


    private Container bookContainer;

    public AjoutAuPanier( ) {
current = this;

        
    // create a new button for the cart icon
    Button cartButton = new Button(FontImage.MATERIAL_SHOPPING_CART);
    cartButton.addActionListener(e -> {
        // navigate to the cart items view
        new LignePaniersUI(current).show();
    });

    // add the cart button to the toolbar
        Toolbar myToolbar = new Toolbar();
    setToolBar(myToolbar);
    myToolbar.addCommandToRightBar("", FontImage.createMaterial(FontImage.MATERIAL_SHOPPING_CART, UIManager.getInstance().getComponentStyle("TitleCommand")), e -> {
        // navigate to the cart items view
        new LignePaniersUI(current).show();
    });
    myToolbar.addCommandToLeftBar("", FontImage.createMaterial(FontImage.MATERIAL_MENU, UIManager.getInstance().getComponentStyle("TitleCommand")), e -> {
            new SidebarClt().show();
        });
    setTitle("Livres");

    bookContainer = new Container(new BoxLayout(BoxLayout.Y_AXIS));
    add(bookContainer);

    ServicePanier sp = new ServicePanier();
    ArrayList<Livre> livres = sp.getAllALivres();
    for (Livre livre : livres) {
        Container singleBook = new Container(new BorderLayout());
        bookContainer.add(singleBook);

        Container bookDetailsContainer = new Container(new BoxLayout(BoxLayout.Y_AXIS));
        singleBook.add(BorderLayout.EAST, bookDetailsContainer);

        Button addToCartButton = new Button("Ajouter au panier");
        addToCartButton.addActionListener(e -> {
            sp.AddToCart(livre.getId());
        });
        bookDetailsContainer.add(addToCartButton);

        // Display the book title and price
        Label title = new Label(livre.getLibelle());
        Label price = new Label(String.valueOf(livre.getPrix()) + " DT");
        singleBook.add(BorderLayout.CENTER, BoxLayout.encloseY(title, price));
    }
}

}
