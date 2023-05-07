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
import com.codename1.l10n.ParseException;
import com.codename1.l10n.SimpleDateFormat;
import com.codename1.ui.events.ActionListener;
import com.mycompany.myapp.entities.Reclamation;
import com.mycompany.myapp.utils.Statics;

import java.io.IOException;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;
import java.util.Map;

/**
 *
 * @author MSI
 */
public class ServiceReclamation {
    public ArrayList<Reclamation> recs;

    public static ServiceReclamation instance = null;
    public boolean resultOK;
    private ConnectionRequest req;

    public ServiceReclamation() {
        req = new ConnectionRequest();
    }

    public static ServiceReclamation getInstance()
    {
        if(instance==null)
        {
            instance = new ServiceReclamation();
        }
        return instance ;
    }


    public boolean addReclamation(Reclamation r) {

        String contenu = r.getContenu();
        String img = r.getImg();
        String etat = r.getEtat();
        Date date = r.getDateRec();
        int iduser = r.getIdUser();
        int idtype = r.getIdType();
        
        
//hehy
        String url = Statics.BASE_URL + "/rec/newJSON?contenu=" + contenu ;
        System.err.println(url);

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

    public ArrayList<Reclamation> parseReclamattion(String jsonText) throws ParseException {
        try {
            recs = new ArrayList<>();
            JSONParser j = new JSONParser();
            Map<String, Object> livresListJson
                    = j.parseJSON(new CharArrayReader(jsonText.toCharArray()));

            List<Map<String, Object>> list = (List<Map<String, Object>>) livresListJson.get("root");
            for (Map<String, Object> obj : list) {
                Reclamation r = new Reclamation();
                float id = Float.parseFloat(obj.get("idRec").toString());
                r.setIdRec((int) id);
                r.setContenu((String) obj.get("contenu".toString()));
                String dateFacString = obj.get("dateRec").toString();
                    DateFormat inputFormat = new SimpleDateFormat("yyyy-MM-dd"); // Input format of the date string
                    Date dateRec = inputFormat.parse(dateFacString); // Convert dateFacString to a Date object

                r.setDateRec(dateRec);
                r.setEtat((String) obj.get("etat".toString()));
                r.setImg((String) obj.get("img".toString()));
                
                
                
            
                recs.add(r);
            }

        } catch (IOException ex) {
            System.out.println(ex.getMessage());
        }
        return recs;
    }

    public ArrayList<Reclamation> getAllReclamation() {
        String url = Statics.BASE_URL + "/rec/front1";
        req.setUrl(url);
        req.setPost(false);
        req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                try {
                    recs = parseReclamattion(new String(req.getResponseData()));
                } catch (ParseException ex) {
                    
                }
                req.removeResponseListener(this);
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        return recs;
    }
    
     public boolean  deleteReclamation(int id){
       String url = Statics.BASE_URL + "/rec/deleteJSON/" +id;

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
