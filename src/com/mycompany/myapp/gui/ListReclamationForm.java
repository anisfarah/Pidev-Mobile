package com.mycompany.myapp.gui;

import com.codename1.components.SpanLabel;
import com.codename1.l10n.DateFormat;
import com.codename1.l10n.SimpleDateFormat;
import com.codename1.ui.Button;
import com.codename1.ui.Command;
import com.codename1.ui.Container;
import com.codename1.ui.Dialog;
import com.codename1.ui.FontImage;
import com.codename1.ui.Form;
import com.codename1.ui.Label;
import com.codename1.ui.TextField;
import com.codename1.ui.Toolbar;
import com.codename1.ui.events.ActionEvent;
import com.codename1.ui.events.ActionListener;
import com.codename1.ui.geom.Dimension;
import com.codename1.ui.layouts.BoxLayout;
import com.codename1.ui.plaf.UIManager;
import com.mycompany.myapp.entities.Reclamation;
import com.mycompany.myapp.services.ServiceReclamation;

import java.util.ArrayList;
import java.util.Date;

public class ListReclamationForm extends Form {

    public ListReclamationForm() {
        Toolbar myToolbar = new Toolbar();
        setToolBar(myToolbar);

        myToolbar.addCommandToLeftBar("", FontImage.createMaterial(FontImage.MATERIAL_MENU, UIManager.getInstance().getComponentStyle("TitleCommand")), e -> {
            new SidebarClt().show();
        });
        setTitle("List Reclamation");
        setLayout(BoxLayout.y());

        /*SpanLabel sp = new SpanLabel();
        sp.setText(ServiceTask.getInstance().getAllTasks().toString());
        add(sp);
         */
        Container searchContainer = new Container(new BoxLayout(BoxLayout.X_AXIS));
        TextField searchField = new TextField("", "Search");
        searchField.setPreferredSize(new Dimension(800, searchField.getPreferredH())); // définit la largeur préférée à 100 pixels
        searchContainer.addAll(searchField);
        add(searchContainer);

        ArrayList<Reclamation> recs = ServiceReclamation.getInstance().getAllReclamation();

        for (Reclamation r : recs) {
            addElement(r);
        }

        searchField.addDataChangeListener((i1, i2) -> {

            ArrayList<Reclamation> recs1 = ServiceReclamation.getInstance().Search(searchField.getText());
            removeAll();

            add(searchContainer);

            for (Reclamation r : recs1) {
                addElement(r);
            }
        });
                    getToolbar().addMaterialCommandToRightBar("", FontImage.MATERIAL_ADD, e -> new AjouterReclamationForm(this).show());

    }

    public void addElement(Reclamation rec) {

        ServiceReclamation sr = new ServiceReclamation();

        Label contenu = new Label("Contenu : " + rec.getContenu());
        Label etat = new Label("Etat : " + rec.getEtat());
        DateFormat inputFormat = new SimpleDateFormat("yyyy-MM-dd"); // format of the date in the Date object
        DateFormat outputFormat = new SimpleDateFormat("d MMMM, yyyy"); // format for displaying month, number of the month, and year

        Date date = rec.getDateRec(); // get the date from the facture object
        String formattedDate = outputFormat.format(date);
        Label dateF = new Label("Date: " + String.valueOf(formattedDate.toUpperCase()));

//        Button detail = new Button("Détails");
//        detail.addActionListener(e -> {
//            Dialog.show("Détails", "Libelle :" + rec.getLibelle()+ "\nPrix : " + livre.getPrix()
//                    + "\nDescription :" + rec.getDescription()
//                    + "\nCatégorie :" + rec.getCategorie()+ "\nEditeur :" + livre.getEditeur()
//                    + "\nDate d'edition :" + rec.getDate_edition().toString(), "OK", null);
//        });
        Button supprimer = new Button("Supprimer");
        supprimer.addActionListener(e -> {
            Dialog alert = new Dialog("Confirmation");
            SpanLabel message = new SpanLabel("Etes-vous sur de vouloir supprimer cet livre?");
            alert.add(message);
            Button ok = new Button("Confirmer");
            Button cancel = new Button(new Command("Annuler"));
            //User clicks on ok to delete account
            ok.addActionListener((ActionListener) (ActionEvent evt) -> {
                sr.deleteReclamation(rec.getIdRec());

                alert.dispose();
                refreshTheme();
            });
            alert.add(cancel);
            alert.add(ok);
            alert.showDialog();
            new ListReclamationForm().show();

        });

        addAll(contenu, dateF, etat, supprimer);

    }

}
