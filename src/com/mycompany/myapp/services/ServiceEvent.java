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
import com.codename1.l10n.ParseException;
import com.codename1.ui.events.ActionListener;
import com.mycompany.myapp.entities.Event;
import com.mycompany.myapp.entities.Facture;
import com.mycompany.myapp.entities.Livre;
import com.mycompany.myapp.utils.Statics;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.Map;


/**
 *
 * @author Pc Anis
 */
public class ServiceEvent {
    public ArrayList<Event> events;

    public static ServiceEvent instance = null;
    public boolean resultOK;
    private ConnectionRequest req;

    public ServiceEvent() {
        req = new ConnectionRequest();
    }

    public static ServiceEvent getInstance() {
        if (instance == null) {
            instance = new ServiceEvent();
        }
        return instance;
    }

    public boolean addEvent(Event e) {

        String nom = e.getNomevent();
        String description = e.getDescription();
        String lieu = e.getLieu();
        float prix = e.getPrix();
        
        


//        String url = Statics.BASE_URL + "/promo/addJSON?code=" + code + "&reduction=" + reduction + "&dateDebut=" + dateDebut + "&dateFin=" + dateFin;

//        req.setUrl(url);
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

//    public void updatePromo(Promo promo) {
//        String url = Statics.BASE_URL + "/promo/editJSON/" + promo.getId()+"?code="+promo.getCode() +"&reduction=" + promo.getReduction();
//        req.setUrl(url);
//        req.setPost(false);
//       
//        req.addArgument("id", String.valueOf(promo.getId()));
//        req.addArgument("code", promo.getCode());
//        req.addArgument("reduction", String.valueOf(promo.getReduction()));
//        req.addArgument("date_debut", promo.getDate_debut().toString());
//        req.addArgument("date_fin", promo.getDate_fin().toString());
//
//        req.addResponseListener((NetworkEvent evt) -> {
//            byte[] data = (byte[]) req.getResponseData();
//            String s = new String(data);
//            System.out.println("Result: " + s);
//        });
//
//        NetworkManager.getInstance().addToQueue(req);
//    }

    public ArrayList<Event> parseEvents(String jsonText) throws ParseException, IOException {
        try {
            events = new ArrayList<>();
            JSONParser j = new JSONParser();
            Map<String, Object> promosListJson
                    = j.parseJSON(new CharArrayReader(jsonText.toCharArray()));

            List<Map<String, Object>> list = (List<Map<String, Object>>) promosListJson.get("root");
            for (Map<String, Object> obj : list) {
                Event p = new Event();
                float id = Float.parseFloat(obj.get("idEvent").toString());
                p.setId((int) id);
                p.setPrix(((int) Float.parseFloat(obj.get("prixEvent").toString())));
//                SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd");
//                Date dateDebut = format.parse(obj.get("dateDebut").toString());
//                p.setDate_debut(dateDebut);
                p.setDescription(obj.get("descEvent").toString());
                p.setLieu(obj.get("lieuEvent").toString());
                 p.setNomevent(obj.get("nomEvent").toString());


                
                events.add(p);
            }

        } catch (IOException ex) {
            System.out.println(ex.getMessage());
        }
        return events;
    }

    public ArrayList<Event> getAllEvents() {
        String url = Statics.BASE_URL + "/event/frontJson";
        req.setUrl(url);
        req.setPost(false);
        req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
               
                try {
                    events = parseEvents(new String(req.getResponseData()));
                } catch (ParseException ex) {
                } catch (IOException ex) {
                }

                req.removeResponseListener(this);
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        return events;
    }

    public boolean deleteEvent(int idEvent) {
        String url = Statics.BASE_URL + "/event/deleteJSON/" + idEvent;

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
