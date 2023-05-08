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
import com.codename1.l10n.DateFormat;
import com.codename1.l10n.SimpleDateFormat;
import com.codename1.ui.events.ActionListener;
import com.mycompany.myapp.entities.Facture;
import com.mycompany.myapp.entities.Livre;
import com.mycompany.myapp.utils.Statics;
import com.mycompany.myapp.utils.UserSession;
import java.io.IOException;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;
import java.util.Map;

/**
 *
 * @author Pc Anis
 */
public class ServiceFacture {

    public ArrayList<Facture> Factures;
    public ArrayList<Livre> Livres;

    public boolean resultOK;
    private ConnectionRequest req;

    public ServiceFacture() {
        req = new ConnectionRequest();
    }

    public ArrayList<Facture> parseCat(String jsonText) {
        try {
            Factures = new ArrayList<>();
            JSONParser j = new JSONParser();

            Map<String, Object> ArticleListJson = j.parseJSON(new CharArrayReader(jsonText.toCharArray()));
            List<Map<String, Object>> list = (List<Map<String, Object>>) ArticleListJson.get("root");
            for (Map<String, Object> obj : list) {
                Facture f = new Facture();

                try {
                    float id = Float.parseFloat(obj.get("idFacture").toString());
                    f.setId(Math.round(id));
                } catch (Exception e1) {
                    System.out.println("error1");
                }

                try {
                    f.setMode_paiement(obj.get("modePaiement").toString());
                } catch (Exception e2) {
                    System.out.println("error2");
                }

                try {
                    f.setMontant_totale(Float.parseFloat(obj.get("mntTotale").toString()));
                } catch (Exception e5) {
                    System.out.println("error3");
                }

                try {
                    String dateFacString = obj.get("dateFac").toString();
                    DateFormat inputFormat = new SimpleDateFormat("yyyy-MM-dd"); // Input format of the date string
                    Date dateFac = inputFormat.parse(dateFacString); // Convert dateFacString to a Date object

                    f.setDate_fac(dateFac);

                } catch (Exception e5) {
                    System.out.println("error3");
                }

                try {
                    Factures.add(f);
                } catch (Exception e6) {
                    System.out.println("error8");
                }
            }

        } catch (IOException ex) {
        }

        return Factures;
    }

    public ArrayList<Facture> getAllFactures() {
                        int id = UserSession.instance.getU().getId();

        String url = Statics.BASE_URL + "/MesFacturesJson/"+id;
        System.out.println(url);
        req.setUrl(url);
        req.setPost(false);
        req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                Factures = parseCat(new String(req.getResponseData()));
                req.removeResponseListener(this);
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        return Factures;
    }

    public ArrayList<Livre> parseDetailsFacture(String jsonText) {
        try {
            Livres = new ArrayList<>();
            JSONParser j = new JSONParser();

            Map<String, Object> ArticleListJson = j.parseJSON(new CharArrayReader(jsonText.toCharArray()));
            List<Map<String, Object>> list = (List<Map<String, Object>>) ArticleListJson.get("root");
            for (Map<String, Object> obj : list) {
                Livre l = new Livre();

                try {
                    float id = Float.parseFloat(obj.get("idFacture").toString());
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
                    float qte = Float.parseFloat(obj.get("qte").toString());
                    l.setQte(Math.round(qte));
                } catch (Exception e5) {
                    System.out.println("error4");
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

    public ArrayList<Livre> getDetailsFacturesClient(int idFacture) {
                        int id = UserSession.instance.getU().getId();

        String url = Statics.BASE_URL + "/detailsFacturesJson/" + idFacture+"/"+id ;
        System.out.println(url);
        req.setUrl(url);
        req.setPost(false);
        req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                Livres = parseDetailsFacture(new String(req.getResponseData()));
                req.removeResponseListener(this);
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        return Livres;
    }
    
      public Boolean AjouterFacture(int idPanier) {
                                  int id = UserSession.instance.getU().getId();

        String url = Statics.BASE_URL + "/ajouterJson/" + idPanier+"/"+id;
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
