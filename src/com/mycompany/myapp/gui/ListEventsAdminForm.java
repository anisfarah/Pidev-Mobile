/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mycompany.myapp.gui;

import com.codename1.components.SpanLabel;
import com.codename1.ui.Button;
import com.codename1.ui.Command;
import com.codename1.ui.Dialog;
import com.codename1.ui.FontImage;
import com.codename1.ui.Form;
import com.codename1.ui.Label;
import com.codename1.ui.Toolbar;
import com.codename1.ui.events.ActionEvent;
import com.codename1.ui.events.ActionListener;
import com.codename1.ui.layouts.BoxLayout;
import com.codename1.ui.plaf.UIManager;
import com.mycompany.myapp.entities.Event;
import com.mycompany.myapp.services.ServiceEvent;
import java.util.ArrayList;

/**
 *
 * @author Pc Anis
 */
public class ListEventsAdminForm extends Form {

    public ListEventsAdminForm(Form previous) {
        Toolbar myToolbar = new Toolbar();
        setToolBar(myToolbar);

        myToolbar.addCommandToLeftBar("", FontImage.createMaterial(FontImage.MATERIAL_MENU, UIManager.getInstance().getComponentStyle("TitleCommand")), e -> {
            new SidebarClt().show();
        });
        setTitle("List des événements");
        setLayout(BoxLayout.y());

        /*SpanLabel sp = new SpanLabel();
        sp.setText(ServiceTask.getInstance().getAllTasks().toString());
        add(sp);
         */
        Label label = new Label("Liste des events :");
        add(label);
        ArrayList<Event> events = ServiceEvent.getInstance().getAllEvents();

        for (Event e : events) {
            addElement(e);
        }

    }

    public void addElement(Event event) {
        ServiceEvent ps = new ServiceEvent();
        ServiceParticipation sp = new ServiceParticipation();

        Label code = new Label("Nomevent : " + event.getNomevent());
        Label reduction = new Label("Description : " + event.getDescription());

        Button detail = new Button("Détails");
        detail.addActionListener(e -> {
            Dialog.show("Détails", "Nom :" + event.getNomevent() + "\nDescription : " + event.getDescription()
                    + "\nPrix :" + event.getPrix()
                    + "\nLieu :" + event.getLieu(), "OK", null);
        });

        Button supprimer = new Button("Supprimer");
        supprimer.addActionListener(e -> {
            Dialog alert = new Dialog("Confirmation");
            SpanLabel message = new SpanLabel("Etes-vous sur de vouloir supprimer cet event?");
            alert.add(message);
            Button ok = new Button("Confirmer");
            Button cancel = new Button(new Command("Annuler"));
            //User clicks on ok to delete account
            ok.addActionListener((ActionListener) (ActionEvent evt) -> {
                ps.deleteEvent(event.getId());

                alert.dispose();
                refreshTheme();
            });
            alert.add(cancel);
            alert.add(ok);
            alert.showDialog();
            new ListEventsAdminForm(this).show();

        });

        addAll(code, reduction, detail, supprimer);

    }

}
