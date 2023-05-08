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
public class ServiceLignePanier {

    public ArrayList<Livre> Livres;

    public static ServicePanier instance = null;
    public boolean resultOK;
    private ConnectionRequest req;

    public ServiceLignePanier() {
        req = new ConnectionRequest();
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
                    System.out.println("error1");
                }

                try {
                    l.setLibelle(obj.get("libelle").toString());
                } catch (Exception e2) {
                    System.out.println("error2");
                }

                try {
                    l.setPrix(Float.parseFloat(obj.get("prix").toString()));
                } catch (Exception e5) {
                    System.out.println("error3");
                }

                try {
                    l.setPrixtot(Float.parseFloat(obj.get("sousTotal").toString()));
                } catch (Exception e5) {
                    System.out.println("error4");
                }

                try {
                    float idLigne = Float.parseFloat(obj.get("idLigne").toString());
                    l.setIdLigne(Math.round(idLigne));
                } catch (Exception e5) {
                    System.out.println("error5");
                }

                try {
                    float qte = Float.parseFloat(obj.get("qte").toString());
                    l.setQte(Math.round(qte));
                } catch (Exception e5) {
                    System.out.println("error6");
                }
                
                  try {
                    float idPanier = Float.parseFloat(obj.get("idPanier").toString());
                    l.setIdPanier(Math.round(idPanier));
                } catch (Exception e5) {
                    System.out.println("error7");
                }

                try {
                    Livres.add(l);
                } catch (Exception e6) {
                    System.out.println("error8");
                }
            }

        } catch (IOException ex) {
        }

        return Livres;
    }

    public ArrayList<Livre> getAllALivresPanier() {
                int id = UserSession.instance.getU().getId();

        String url = Statics.BASE_URL + "/lignepanier/json/"+id;
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

    public Boolean DeleteItemCart(int idLigne) {
        String url = Statics.BASE_URL + "/deleteLigneJson/" + idLigne;
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

    
    
    
    public Boolean DeleteAllItemsCart(int idPanier) {
        String url = Statics.BASE_URL + "/deleteAllLignesJson/" + idPanier;
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
    
     public Boolean IncrementerQteCart(int idLigne) {
        String url = Statics.BASE_URL + "/ligne-panierJson/" + idLigne+"/plus";
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
     
     public Boolean DeccrementerQteCart(int idLigne) {
        String url = Statics.BASE_URL + "/ligne-panierJson/" + idLigne+"/minus";
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
