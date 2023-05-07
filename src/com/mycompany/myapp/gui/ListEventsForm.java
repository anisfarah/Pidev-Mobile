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
import com.mycompany.myapp.entities.Participation;
import com.mycompany.myapp.services.ServiceEvent;
import java.util.ArrayList;

/**
 *
 * @author Pc Anis
 */
public class ListEventsForm extends Form {

    public ListEventsForm(Form previous) {
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

        Button participer = new Button("Participer");

        participer.addActionListener(e -> {

            if (ServiceParticipation.getInstance().addParticipation(event)) {
                Dialog.show("Success", "Participation réussi de l'event " + event.getNomevent(), new Command("OK"));
            } else {
                Dialog.show("ERROR", "Participation échouée de l'event", new Command("OK"));
            }

        });

        addAll(code, reduction, detail, participer);

    }

}
