/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mycompany.myapp.gui;

import com.codename1.components.SpanLabel;
import com.codename1.io.CharArrayReader;
import com.codename1.io.ConnectionRequest;
import com.codename1.io.JSONParser;
import com.codename1.io.Log;
import com.codename1.io.NetworkManager;
import com.codename1.ui.Button;
import com.codename1.ui.Container;
import com.codename1.ui.FontImage;
import com.codename1.ui.Form;
import com.codename1.ui.Label;
import com.codename1.ui.Toolbar;
import com.codename1.io.NetworkEvent;
import com.codename1.io.NetworkManager;
import com.codename1.ui.Command;
import com.codename1.ui.Component;
import com.codename1.ui.Dialog;
import com.codename1.ui.Display;
import com.codename1.ui.Slider;
import com.codename1.ui.TextField;
import com.codename1.ui.events.ActionEvent;
import com.codename1.ui.events.ActionListener;
import com.codename1.ui.layouts.BorderLayout;
import com.codename1.ui.layouts.BoxLayout;
import com.codename1.ui.layouts.FlowLayout;
import com.codename1.ui.plaf.UIManager;
import com.mycompany.myapp.entities.Livre;
import com.mycompany.myapp.services.ServiceLignePanier;
import com.mycompany.myapp.services.ServicePanier;
import com.mycompany.myapp.utils.Statics;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.Map;

/**
 *
 * @author Pc Anis
 */
public class LignePaniersUI extends Form {

    private Container bookContainer;

    public LignePaniersUI(Form previous) {
        ServiceLignePanier slp = new ServiceLignePanier();
        bookContainer = new Container(new BoxLayout(BoxLayout.Y_AXIS));
        add(bookContainer);

        ArrayList<Livre> livres = slp.getAllALivresPanier();
        Toolbar myToolbar = new Toolbar();
        setToolBar(myToolbar);
        myToolbar.addCommandToRightBar("", FontImage.createMaterial(FontImage.MATERIAL_DELETE_OUTLINE, UIManager.getInstance().getComponentStyle("TitleCommand")), e -> {
            Dialog alert = new Dialog("Attention");
            SpanLabel message = new SpanLabel("Etes-vous sur de vouloir vider: " + " ?");
            alert.add(message);
            Button ok = new Button("Confirmer");
            Button cancel = new Button(new Command("Annuler"));
            //User clicks on ok to delete account
            ok.addActionListener(new ActionListener() {
                public void actionPerformed(ActionEvent evt) {
                    Livre livreP = livres.get(0);
                    int idPanier = livreP.getIdPanier();
                    slp.DeleteAllItemsCart(idPanier);
                    alert.dispose();
                }
            }
            );
            alert.add(cancel);
            alert.add(ok);
            alert.showDialog();
            new LignePaniersUI(previous).show();

        });

        ArrayList<Livre> lignePaniers = slp.getAllALivresPanier();
        if (livres.isEmpty()) {
            Label emptyCartLabel = new Label("          Le panier est vide pour le moment");
            emptyCartLabel.getAllStyles().setMarginTop(Display.getInstance().getDisplayHeight() / 3);
            bookContainer.add(emptyCartLabel);
        } else {
            for (Livre livre : livres) {
                Container singleBook = new Container(new BorderLayout());
                bookContainer.add(singleBook);
                Button crossButton = new Button();
                crossButton.setIcon(FontImage.createMaterial(FontImage.MATERIAL_CLOSE, "X", 4));
                singleBook.add(BorderLayout.WEST, crossButton);

                crossButton.getAllStyles().setBgColor(0xF36B08);
                crossButton.addActionListener(e -> {
                    Dialog alert = new Dialog("Attention");
                    SpanLabel message = new SpanLabel("Etes-vous sur de vouloir supprimer ce livre: " + livre.getLibelle() + " ?");
                    alert.add(message);
                    Button ok = new Button("Confirmer");
                    Button cancel = new Button(new Command("Annuler"));
                    ok.addActionListener(new ActionListener() {
                        public void actionPerformed(ActionEvent evt) {
                            slp.DeleteItemCart(livre.getIdLigne());
                            alert.dispose();
                        }
                    }
                    );
                    alert.add(cancel);
                    alert.add(ok);
                    alert.showDialog();
                    new LignePaniersUI(previous).show();
                });
                Container bookDetailsContainer = new Container(new BoxLayout(BoxLayout.Y_AXIS));
                singleBook.add(BorderLayout.CENTER, bookDetailsContainer);

                Label title = new Label(livre.getLibelle() + " (X" + livre.getQte() + ")");
                Label price = new Label(String.valueOf(livre.getPrix()) + " DT");
                Label pxTot = new Label("Total: " + String.valueOf(livre.getPrixtot()) + " DT");
                bookDetailsContainer.add(title);
                bookDetailsContainer.add(price);
                bookDetailsContainer.add(pxTot);
                Container quantityContainer = new Container(new BoxLayout(BoxLayout.X_AXIS));

                Button minusButton = new Button("-");
                minusButton.addActionListener(e -> {
                    if (livre.getQte() > 1) {
                        slp.DeccrementerQteCart(livre.getIdLigne());
                        livre.setQte(livre.getQte() - 1);
                        livre.setPrixtot(livre.getQte() * livre.getPrix());
                        new LignePaniersUI(previous).show();

                    }
                });
                quantityContainer.add(minusButton);

                Label quantityLabel = new Label(String.valueOf(livre.getQte()));
                quantityLabel.getAllStyles().setAlignment(Component.CENTER);
                quantityContainer.add(quantityLabel);

                Button plusButton = new Button("+");
                plusButton.addActionListener(e -> {

                    slp.IncrementerQteCart(livre.getIdLigne());
                    livre.setQte(livre.getQte() + 1);
                    livre.setPrixtot(livre.getQte() * livre.getPrix());
                    new LignePaniersUI(previous).show();

                });
                quantityContainer.add(plusButton);
                bookDetailsContainer.add(quantityContainer);

            }

        }
             
        getToolbar().addMaterialCommandToLeftBar("", FontImage.MATERIAL_ARROW_BACK,
                e -> previous.showBack());
  

        Label arrowLabel = new Label(FontImage.createMaterial(FontImage.MATERIAL_KEYBOARD_ARROW_DOWN, UIManager.getInstance().getComponentStyle("Label")));
Container arrowContainer = new Container(new BorderLayout());
arrowContainer.add(BorderLayout.SOUTH, arrowLabel);
getContentPane().add(FlowLayout.encloseBottom(arrowContainer));

        

        setTitle("Panier");
    }

}
