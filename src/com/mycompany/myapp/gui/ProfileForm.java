/*
 * Copyright (c) 2016, Codename One
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated 
 * documentation files (the "Software"), to deal in the Software without restriction, including without limitation 
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, 
 * and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions 
 * of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, 
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A 
 * PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT 
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF 
 * CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE 
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE. 
 */
package com.mycompany.myapp.gui;

import com.codename1.capture.Capture;
import com.codename1.components.ImageViewer;
import com.codename1.components.InfiniteProgress;
import com.codename1.components.ScaleImageLabel;
import com.codename1.components.SpanLabel;

import com.codename1.io.FileSystemStorage;
import com.codename1.io.MultipartRequest;
import com.codename1.io.NetworkManager;
import com.codename1.l10n.L10NManager;
import com.codename1.l10n.ParseException;
import com.codename1.ui.Button;
import com.codename1.ui.CheckBox;
import com.codename1.ui.Command;
import com.codename1.ui.Component;
import com.codename1.ui.Dialog;
import com.codename1.ui.Display;
import com.codename1.ui.Image;
import com.codename1.ui.Label;
import com.codename1.ui.TextArea;
import com.codename1.ui.TextField;
import com.codename1.ui.Toolbar;
import com.codename1.ui.events.ActionEvent;
import com.codename1.ui.events.ActionListener;
import com.codename1.ui.layouts.BorderLayout;
import com.codename1.ui.layouts.BoxLayout;
import com.codename1.ui.layouts.FlowLayout;
import com.codename1.ui.layouts.GridLayout;
import com.codename1.ui.layouts.LayeredLayout;
import com.codename1.ui.plaf.Style;
import com.codename1.ui.spinner.Picker;
import com.codename1.ui.util.Resources;

import com.mycompany.myapp.services.UserServices;
import com.mycompany.myapp.utils.UserSession;
import com.codename1.l10n.SimpleDateFormat;
import com.codename1.ui.EncodedImage;
import com.codename1.ui.Font;
import com.codename1.ui.FontImage;
import com.codename1.ui.Form;
import com.codename1.ui.Graphics;
import com.codename1.ui.URLImage;
import com.mycompany.myapp.utils.Statics;
import com.mycompany.myapp.utils.UserSession;
import java.io.IOException;
import java.util.Random;
import com.mycompany.myapp.entities.User;
import com.mycompany.myapp.gui.BaseForm;


/**
 * The user profile form
 *
 * @author Shai Almog
 */
public class ProfileForm extends Form {

    UserServices us;
    String Imagecode;
    String filePath = "";

    public ProfileForm(Resources res, User user) {

        super("Newsfeed", BoxLayout.y());
        this.us = UserServices.getInstance();
        Toolbar tb = new Toolbar(true);
        setToolbar(tb);
        getTitleArea().setUIID("Container");
        setTitle("Profile");
        getContentPane().setScrollVisible(false);

//        super.addSideMenu(res);

        tb.addSearchCommand(e -> {
        });

//        Image img = res.getImage("profile-background.jpg");
//        if (img.getHeight() > Display.getInstance().getDisplayHeight() / 3) {
//            img = img.scaledHeight(Display.getInstance().getDisplayHeight() / 3);
//        }
//        ScaleImageLabel sl = new ScaleImageLabel(img);
//        sl.setUIID("BottomPad");
//        sl.setBackgroundType(Style.BACKGROUND_IMAGE_SCALED_FILL);
//
//        Label facebook = new Label("786 followers", res.getImage("facebook-logo.png"), "BottomPad");
//        Label twitter = new Label("486 followers", res.getImage("twitter-logo.png"), "BottomPad");
//        facebook.setTextPosition(BOTTOM);
//        twitter.setTextPosition(BOTTOM);
//
//        EncodedImage enc = (EncodedImage) res.getImage("round-mask.png");
//        Image roundMask = Image.createImage(enc.getWidth(), enc.getHeight(), 0xff000000);
//        Graphics gr = roundMask.getGraphics();
//        gr.setColor(0xffffff);
//        gr.fillArc(0, 0, enc.getWidth(), enc.getWidth(), 0, 360);
//        Object mask = roundMask.createMask();

        add(LayeredLayout.encloseIn(
//                sl,
                BorderLayout.south(
                        GridLayout.encloseIn(1,
                                FlowLayout.encloseCenter()
                        )
                )));
        if (user.getNom() == null || user.getPrenom() == null || Long.toString(user.getTel()) == null) {
            Dialog.show("Alert", "complÃ©ter ton inscription", new Command("OK"));

        }

        TextField prenom = new TextField(user.getPrenom());
        prenom.setUIID("TextFieldBlack");
        addStringValue("Prenom", prenom);

        TextField nom = new TextField(user.getNom());
        nom.setUIID("TextFieldBlack");
        addStringValue("Nom", nom);

        TextField email = new TextField(user.getEmail(), "E-Mail", 20, TextField.EMAILADDR);
        email.setUIID("TextFieldBlack");
        addStringValue("E-Mail", email);

        TextField tel = new TextField(Long.toString(user.getTel()), TextField.PHONENUMBER);
        tel.setUIID("TextFieldBlack");
        addStringValue("tel", tel);

        TextField adresse = new TextField(user.getAdresse());
        adresse.setUIID("TextFieldBlack");
        addStringValue("Adresse", adresse);

        Picker birthdayInput = new Picker();
        birthdayInput.setType(Display.PICKER_TYPE_DATE);
        birthdayInput.setDate(user.getDatedenaissance());

        birthdayInput.setUIID("TextFieldBlack");

        addStringValue("Date de naissance", birthdayInput);

        Button edit = new Button("Modifier");

        addStringValue("", edit);

        Button supprimer = new Button("Supprimer");

        addStringValue("", supprimer);

        supprimer.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent evt) {

                if (Dialog.show("Confirmer", "Do you want to proceed?", "OK", "Cancel")) {

                    us.deleteUSER(UserSession.instance.getU().getId());
                    UserSession.instance.cleanUserSession();

                    new SignInForm(res).show();

                }
            }
        });
        edit.requestFocus();

        edit.addActionListener((ActionEvent e) -> {

            if (edit.getText().equals("Modifier")) {
                edit.setText("Sauvgarder");
                prenom.setEditable(true);
                nom.setEditable(true);
                email.setEditable(true);
                tel.setEditable(true);
                adresse.setEditable(true);

            } else {

                if (Dialog.show("Confirmer", "Do you want to proceed?", "OK", "Cancel")) {

                    if (!nom.getText().equals("")) {
                        user.setNom(nom.getText());
                    }
                    if (!prenom.getText().equals("")) {
                        user.setPrenom(prenom.getText());

                    }

                    L10NManager l10n = L10NManager.getInstance();

                    user.setDatte(l10n.formatDateShortStyle(birthdayInput.getDate()));
                    System.err.println(l10n.formatDateShortStyle(birthdayInput.getDate()));

                    if (!tel.getText().equals("")) {
                        user.setTel(Integer.parseInt(tel.getText()));
                    }

                    if (!email.getText().equals("")) {

                        user.setEmail(email.getText());
                    }
                    if (!adresse.getText().equals("")) {
                        user.setAdresse(adresse.getText());
                    }

                    if (us.updateUser(UserSession.instance.getU())) {

                        new ProfileForm(res, UserSession.instance.getU()).show();
                    }

                } else {

                    new ProfileForm(res, UserSession.instance.getU()).show();
                }
                //User clicks on ok to delete account

                edit.setText("Modifier");
                prenom.setEditable(false);
                nom.setEditable(false);
                email.setEditable(false);
                tel.setEditable(false);
                adresse.setEditable(false);

            }

        });

        prenom.setEditable(false);
        nom.setEditable(false);
        email.setEditable(false);
        tel.setEditable(false);
        adresse.setEditable(false);

    }

    private void addStringValue(String s, Component v) {
        add(BorderLayout.west(new Label(s, "PaddedLabel")).
                add(BorderLayout.CENTER, v));
//        add(createLineSeparator(0xeeeeee));
    }

    private void add2bo(Component k, Component v) {
        add(BorderLayout.west(k).
                add(BorderLayout.CENTER, v));
//        add(createLineSeparator(0xeeeeee));
    }

}
