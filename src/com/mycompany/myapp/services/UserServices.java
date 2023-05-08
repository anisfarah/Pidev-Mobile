/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mycompany.myapp.services;

import com.codename1.io.CharArrayReader;
import com.codename1.io.ConnectionRequest;
import com.codename1.io.JSONParser;
import com.codename1.io.NetworkEvent;
import com.codename1.io.NetworkManager;
import com.codename1.ui.events.ActionListener;

import com.codename1.l10n.ParseException;
import com.codename1.ui.Command;
import com.codename1.ui.Dialog;
import com.mycompany.myapp.entities.User;
import com.mycompany.myapp.utils.Statics;
import com.mycompany.myapp.utils.UserSession;
import java.io.IOException;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;
import java.util.Map;

/**
 *
 * @author bhk
 */
public class UserServices {

    public static UserServices instance;
    private final ConnectionRequest con;
    ArrayList<User> users = new ArrayList<>();
    public boolean ResultOK;
//    private ConnectionRequest req;
    public User user;

    public UserServices() {
        con = new ConnectionRequest();
    }

    public static UserServices getInstance() {
        if (instance == null) {
            instance = new UserServices();
        }
        return instance;
    }
    boolean result;

    public boolean RegisterAction(String email, String password, String password_confirmation, String nom, String prenom, String tel, String adresse) {

        // création d'une nouvelle demande de connexion
        String url = Statics.BASE_URL + "/user/signup" + "?email=" + email + "&password=" + password + "&confirm_password=" + password_confirmation + "&nom=" + nom + "&prenom=" + prenom + "&tel=" + tel + ""
                + "&adresse=" + adresse;
        con.setUrl(url);// Insertion de l'URL de notre demande de connexion

        con.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {

                result = con.getResponseCode() == 200;
                String str = new String(con.getResponseData());//Récupération de la réponse du serveur
                System.out.println(str);//Affichage de la réponse serveur sur la console
                Dialog.show("Alert", str, new Command("OK"));

                con.removeResponseListener(this);

            }
        });

        NetworkManager.getInstance().addToQueueAndWait(con);// Ajout de notre demande de connexion à la file d'attente du NetworkManager
        return result;
    }

    public boolean loginAction(String email, String password) {

        // création d'une nouvelle demande de connexion
        String url = Statics.BASE_URL + "/user/signin" + "?email=" + email + "&password=" + password;
        con.setUrl(url);// Insertion de l'URL de notre demande de connexion

        con.addResponseListener((e) -> {
            result = con.getResponseCode() == 200;

            if (result) {
                try {
                    String str = new String(con.getResponseData());//Récupération de la réponse du serveur
                    System.out.println(str);//Affichage de la réponse serveur sur la console
                    parseListUserJson(new String(con.getResponseData()));

                } catch (ParseException ex) {

                }
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(con);// Ajout de notre demande de connexion à la file d'attente du NetworkManager
        return result;
    }

    public User parseListUserJson(String json) throws ParseException {

        User u = new User();
        try {
            JSONParser j = new JSONParser();

            Map<String, Object> obj = j.parseJSON(new CharArrayReader(json.toCharArray()));
            u.setId(((Double) obj.get("idUser")).intValue());
            u.setEmail(obj.get("email").toString());
            if (obj.get("datedenaissance") != null) {
                u.setDatedenaissance(new Date((((Double) ((Map<String, Object>) obj.get("datedenaissance")).get("timestamp")).longValue() * 1000)));
            }

            if (obj.get("nom") != null) {
                u.setNom(obj.get("nom").toString());
            }
            if (obj.get("prenom") != null) {
                u.setPrenom(obj.get("prenom").toString());
            }

            if (obj.get("tel") != null) {
                double telDouble = Double.parseDouble(obj.get("tel").toString());
                u.setTel((int) telDouble);
            }

            if (obj.get("adresse") != null) {
                u.setAdresse(obj.get("adresse").toString());
            }

            if (obj.get("password") != null) {
                u.setPassword(obj.get("password").toString());
            }

            UserSession z = UserSession.getInstance(u);
            System.out.println(z);

        } catch (IOException ex) {
        }

        return u;
    }

    public boolean updateUser(User user) {

        String url = Statics.BASE_URL
                + "/user/editUser?"
                + "id=" + user.getId()
                + "&nom=" + user.getNom()
                + "&adresse=" + user.getAdresse()
                + "&prenom=" + user.getPrenom()
                + "&email=" + user.getEmail()
                + "&tel=" + user.getTel()
                + "&datedenaissance=" + user.getDatte();

        System.err.println(user);

        ConnectionRequest req = new ConnectionRequest(url);
        req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                ResultOK = req.getResponseCode() == 200;
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        return ResultOK;
    }

    public User getUser(String id) {
        String url = Statics.BASE_URL + "getUser?id=" + id;
        con.setUrl(url);
        con.setPost(false);
        con.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                try {
                    user = parseListUserJson(new String(con.getResponseData()));
                } catch (ParseException ex) {

                }
                con.removeResponseListener(this);

            }
        });
        NetworkManager.getInstance().addToQueueAndWait(con);
        return user;
    }

    public boolean updatepassword(String email, String m) {

        String url = Statics.BASE_URL
                + "/user/updatepassword?"
                + "email=" + email
                + "&password=" + m;

        ConnectionRequest req = new ConnectionRequest(url);
        req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                ResultOK = req.getResponseCode() == 200;
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        return ResultOK;
    }

    public boolean checkemail(String email) {

        String url = Statics.BASE_URL
                + "/user/checkemail?"
                + "email=" + email;

        ConnectionRequest req = new ConnectionRequest(url);
        req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                con.removeResponseListener(this);
                ResultOK = req.getResponseCode() == 200;

            }
        });

        NetworkManager.getInstance().addToQueueAndWait(req);
        return ResultOK;
    }

    public boolean deleteUSER(int id) {
        String url = Statics.BASE_URL + "/user/deletedisUser?id=" + id;
        ConnectionRequest req = new ConnectionRequest(url);
        req.setUrl(url);
        req.setHttpMethod("DELETE");
        req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                ResultOK = req.getResponseCode() == 200; //Code HTTP 200 OK
                req.removeResponseListener(this);
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        return ResultOK;
    }
}
