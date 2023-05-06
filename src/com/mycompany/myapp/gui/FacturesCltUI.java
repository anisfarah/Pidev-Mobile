/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mycompany.myapp.gui;

import com.codename1.l10n.DateFormat;
import com.codename1.l10n.SimpleDateFormat;
import com.codename1.ui.Button;
import com.codename1.ui.Container;
import com.codename1.ui.FontImage;
import com.codename1.ui.Form;
import com.codename1.ui.Label;
import com.codename1.ui.Toolbar;
import com.codename1.ui.layouts.BorderLayout;
import com.codename1.ui.layouts.BoxLayout;
import com.codename1.ui.plaf.UIManager;
import com.mycompany.myapp.entities.Livre;
import com.mycompany.myapp.entities.Facture;
import com.mycompany.myapp.services.ServiceFacture;
import com.mycompany.myapp.services.ServicePanier;
import java.util.ArrayList;
import java.util.Date;

/**
 *
 * @author Pc Anis
 */
public class FacturesCltUI extends Form {

    private Container bookContainer;

    public FacturesCltUI(Form previous) {

        // add the cart button to the toolbar
        Toolbar myToolbar = new Toolbar();
        setToolBar(myToolbar);

        myToolbar.addCommandToLeftBar("", FontImage.createMaterial(FontImage.MATERIAL_MENU, UIManager.getInstance().getComponentStyle("TitleCommand")), e -> {
            new SidebarClt().show();
        });
        setTitle("Mes Factures");

        bookContainer = new Container(new BoxLayout(BoxLayout.Y_AXIS));
        add(bookContainer);

        ServiceFacture sf = new ServiceFacture();
        ArrayList<Facture> factures = sf.getAllFactures();
        for (Facture facture : factures) {
            Container singleBook = new Container(new BorderLayout());
            bookContainer.add(singleBook);

            Container bookDetailsContainer = new Container(new BoxLayout(BoxLayout.Y_AXIS));
            singleBook.add(BorderLayout.EAST, bookDetailsContainer);

            Button DetailsButton = new Button("Détails");
            DetailsButton.addActionListener(e -> {
               
            new DetailsFacturesClt(facture.getId()).show();
                });
            bookDetailsContainer.add(DetailsButton);

            DateFormat inputFormat = new SimpleDateFormat("yyyy-MM-dd"); // format of the date in the Date object
            DateFormat outputFormat = new SimpleDateFormat("d MMMM, yyyy"); // format for displaying month, number of the month, and year

            Date date = facture.getDate_fac(); // get the date from the facture object
            String formattedDate = outputFormat.format(date);
            Label paiement = new Label("Mode paiement: "+ facture.getMode_paiement());
            Label dateF = new Label("Date: "+String.valueOf(formattedDate.toUpperCase()));
            singleBook.add(BorderLayout.CENTER, BoxLayout.encloseY(paiement, dateF));
        }
    }

}
