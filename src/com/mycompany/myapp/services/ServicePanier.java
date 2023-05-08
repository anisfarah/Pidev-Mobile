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
import com.mycompany.myapp.entities.Livre;
import com.mycompany.myapp.utils.Statics;
import com.mycompany.myapp.utils.UserSession;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.Map;

/**
 *
 * @author Pc Anis
 */
public class ServicePanier {

    public ArrayList<Livre> Livres;
    public static ServicePanier instance = null;
    public boolean resultOK;
    private ConnectionRequest req;

    public ServicePanier() {
        req = new ConnectionRequest();
    }

    public static ServicePanier getInstance() {
        if (instance == null) {
            instance = new ServicePanier();
        }
        return instance;
    }

    public ArrayList<Livre> parseCat(String jsonText) {
        try {
            Livres = new ArrayList<>();
            JSONParser j = new JSONParser();

            Map<String, Object> ArticleListJson = j.parseJSON(new CharArrayReader(jsonText.toCharArray()));
            List<Map<String, Object>> list = (List<Map<String, Object>>) ArticleListJson.get("root");
            for (Map<String, Object> obj : list) {
                Livre l = new Livre();

                try {

                    float id = Float.parseFloat(obj.get("idLivre").toString());
                    l.setId(Math.round(id));
                } catch (Exception e1) {
                    System.out.println("houni1");
                }

                try {
                    l.setLibelle(obj.get("libelle").toString());
                } catch (Exception e2) {
                    System.out.println("houni2");
                }

                try {
                    l.setDescription(obj.get("description").toString());
                } catch (Exception e4) {
                    System.out.println("houni4");
                }
                try {
                    l.setCategorie(obj.get("categorie").toString());
                } catch (Exception e5) {
                    System.out.println("houni5");
                }
                try {
                    l.setPrix(Float.parseFloat(obj.get("prix").toString()));
                } catch (Exception e5) {
                    System.out.println("houni5");
                }
                try {
                    l.setLangue(obj.get("langue").toString());
                } catch (Exception e5) {
                    System.out.println("houni5");
                }
                try {
                    l.setImage(obj.get("image").toString());
                } catch (Exception e5) {
                    System.out.println("houni5");
                }
                try {
                    l.setEditeur(obj.get("editeur").toString());
                } catch (Exception e5) {
                    System.out.println("houni6");
                }
                try {
                    l.setPrix(Float.parseFloat(obj.get("prix").toString()));
                } catch (Exception e5) {
                    System.out.println("houni5");
                }
                
                

                try {
                    Livres.add(l);
                } catch (Exception e6) {
                    System.out.println("houni6");
                }
            }

        } catch (IOException ex) {

        }
        return Livres;
    }

    public ArrayList<Livre> getAllALivres() {
        String url = Statics.BASE_URL + "/livre/json";
        System.out.println(url);
        req.setUrl(url);
        req.setPost(false);
        req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                Livres = parseCat(new String(req.getResponseData()));
                req.removeResponseListener(this);
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        return Livres;
    }

//   public ArrayList<Livre> getAllALivresPanier() {
//        String url = Statics.BASE_URL + "/lignepanier/json";
//        System.out.println(url);
//        req.setUrl(url);
//        req.setPost(false);
//        req.addResponseListener(new ActionListener<NetworkEvent>() {
//            @Override
//            public void actionPerformed(NetworkEvent evt) {
//                Livres = parseCat(new String(req.getResponseData()));
//                req.removeResponseListener(this);
//            }
//        });
//        NetworkManager.getInstance().addToQueueAndWait(req);
//        return Livres;
//    }

   
      
   
   
    public Boolean AddToCart(int livreId) {
        
        int id = UserSession.instance.getU().getId();
        System.err.println( UserSession.instance.getU().getId());
        String url = Statics.BASE_URL + "/panier/newJson/" + livreId+"/"+id;
        System.out.println(url);
        req.setUrl(url);
        req.setPost(false);
        req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                req.removeResponseListener(this);
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        return resultOK;
    }

}
