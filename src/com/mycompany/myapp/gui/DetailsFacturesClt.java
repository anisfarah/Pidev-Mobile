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
import com.mycompany.myapp.entities.Facture;
import com.mycompany.myapp.entities.Livre;
import com.mycompany.myapp.services.ServiceFacture;
import java.util.ArrayList;
import java.util.Date;

/**
 *
 * @author Pc Anis
 */
public class DetailsFacturesClt extends Form {

    private Container bookContainer;

    public DetailsFacturesClt(int idFacture) { // Ajout du paramètre idFacture

        Toolbar myToolbar = new Toolbar();
        setToolBar(myToolbar);

        myToolbar.addCommandToLeftBar("", FontImage.createMaterial(FontImage.MATERIAL_MENU, UIManager.getInstance().getComponentStyle("TitleCommand")), e -> {
            new SidebarClt().show();
        });
        setTitle("Details");

        bookContainer = new Container(new BoxLayout(BoxLayout.Y_AXIS));
        add(bookContainer);

        ServiceFacture sf = new ServiceFacture();
        ArrayList<Livre> livres = sf.getDetailsFacturesClient(idFacture); // Utilisation de l'idFacture passé en paramètre
        for (Livre livre : livres) {
            Container singleBook = new Container(new BorderLayout());
            bookContainer.add(singleBook);

            Container bookDetailsContainer = new Container(new BoxLayout(BoxLayout.Y_AXIS));
            singleBook.add(BorderLayout.EAST, bookDetailsContainer);

                Label title = new Label(livre.getLibelle() + " (X" + livre.getQte() + ")");
            Label price = new Label(String.valueOf(livre.getPrix()) + " DT");
            Label Total = new Label("Total: " + String.valueOf(livre.getPrixtot()) + " DT");

            singleBook.add(BorderLayout.CENTER, BoxLayout.encloseY(title, price, Total));

        }
    }
}
