/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mycompany.myapp.services;

import com.mycompany.myapp.entities.Promo;
import com.codename1.io.CharArrayReader;
import com.codename1.io.ConnectionRequest;
import com.codename1.io.JSONParser;
import com.codename1.io.NetworkEvent;
import com.codename1.io.NetworkManager;
import com.codename1.l10n.ParseException;
import com.codename1.l10n.SimpleDateFormat;
import com.codename1.ui.events.ActionListener;
import com.mycompany.myapp.utils.Statics;
import java.io.IOException;

import java.util.List;
import java.util.Map;
import java.util.ArrayList;
import java.util.Date;

/**
 *
 * @author MSI
 */
public class PromoService {

    public ArrayList<Promo> promos;

    public static PromoService instance = null;
    public boolean resultOK;
    private ConnectionRequest req;

    public PromoService() {
        req = new ConnectionRequest();
    }

    public static PromoService getInstance() {
        if (instance == null) {
            instance = new PromoService();
        }
        return instance;
    }

    public boolean addPromo(Promo p) {

        String code = p.getCode();
        double reduction = p.getReduction();
        Date dateDebut = p.getDate_debut();
        Date dateFin = p.getDate_fin();

        String url = Statics.BASE_URL + "/promo/addJSON?code=" + code + "&reduction=" + reduction + "&dateDebut=" + dateDebut + "&dateFin=" + dateFin;

        req.setUrl(url);
        req.setPost(false);

        req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                resultOK = req.getResponseCode() == 200; //Code HTTP 200 OK
                req.removeResponseListener(this);
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        return resultOK;
    }

    public void updatePromo(Promo promo) {
        String url = Statics.BASE_URL + "/promo/editJSON/" + promo.getId()+"?code="+promo.getCode() +"&reduction=" + promo.getReduction();
        req.setUrl(url);
        req.setPost(false);
       
        req.addArgument("id", String.valueOf(promo.getId()));
        req.addArgument("code", promo.getCode());
        req.addArgument("reduction", String.valueOf(promo.getReduction()));
        req.addArgument("date_debut", promo.getDate_debut().toString());
        req.addArgument("date_fin", promo.getDate_fin().toString());

        req.addResponseListener((NetworkEvent evt) -> {
            byte[] data = (byte[]) req.getResponseData();
            String s = new String(data);
            System.out.println("Result: " + s);
        });

        NetworkManager.getInstance().addToQueue(req);
    }

    public ArrayList<Promo> parsePromos(String jsonText) throws ParseException {
        try {
            promos = new ArrayList<>();
            JSONParser j = new JSONParser();
            Map<String, Object> promosListJson
                    = j.parseJSON(new CharArrayReader(jsonText.toCharArray()));

            List<Map<String, Object>> list = (List<Map<String, Object>>) promosListJson.get("root");
            for (Map<String, Object> obj : list) {
                Promo p = new Promo();
                float id = Float.parseFloat(obj.get("id").toString());
                p.setId((int) id);
                p.setReduction(((int) Float.parseFloat(obj.get("reduction").toString())));
                SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd");
                Date dateDebut = format.parse(obj.get("dateDebut").toString());
                p.setDate_debut(dateDebut);
                Date dateFin = format.parse(obj.get("dateFin").toString());
                p.setDate_fin(dateFin);

                if (obj.get("code") == null) {
                    p.setCode("null");
                } else {
                    p.setCode(obj.get("code").toString());
                }
                promos.add(p);
            }

        } catch (IOException ex) {
            System.out.println(ex.getMessage());
        }
        return promos;
    }

    public ArrayList<Promo> getAllPromos() {
        String url = Statics.BASE_URL + "/promo/AllPromos";
        req.setUrl(url);
        req.setPost(false);
        req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                try {
                    promos = parsePromos(new String(req.getResponseData()));
                } catch (ParseException ex) {

                }
                req.removeResponseListener(this);
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        return promos;
    }

    public boolean deletePromo(int id) {
        String url = Statics.BASE_URL + "/promo/deleteJSON/" + id;

        req.setUrl(url);

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
