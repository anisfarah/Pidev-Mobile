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
import com.codename1.l10n.SimpleDateFormat;
import com.codename1.ui.events.ActionListener;
import com.mycompany.myapp.entities.Livre;

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
public class LivreService {
    public ArrayList<Livre> livres;

    public static LivreService instance = null;
    public boolean resultOK;
    private ConnectionRequest req;

    public LivreService() {
        req = new ConnectionRequest();
    }

    public static LivreService getInstance()
    {
        if(instance==null)
        {
            instance = new LivreService();
        }
        return instance ;
    }


    public boolean addLivre(Livre l) {

        String libelle = l.getLibelle();
        String categorie = l.getCategorie();
        String description = l.getDescription();
        String editeur = l.getEditeur();
        Date dateEdition = l.getDate_edition();
        float prix = l.getPrix();
        

        String url = Statics.BASE_URL + "/livre/addJSON" + libelle + "/" + categorie + "/" + description + "/" + editeur + "/" + dateEdition + "/" + prix;

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

    public ArrayList<Livre> parseLivres(String jsonText) throws ParseException {
        try {
            livres = new ArrayList<>();
            JSONParser j = new JSONParser();
            Map<String, Object> livresListJson
                    = j.parseJSON(new CharArrayReader(jsonText.toCharArray()));

            List<Map<String, Object>> list = (List<Map<String, Object>>) livresListJson.get("root");
            for (Map<String, Object> obj : list) {
                Livre l = new Livre();
                float id = Float.parseFloat(obj.get("idLivre").toString());
                l.setIdLivre((int) id);
                l.setLibelle((String) obj.get("libelle".toString()));
                l.setPrix(Float.parseFloat(obj.get("prix").toString()));
                l.setCategorie((String) obj.get("categorie".toString()));
                l.setEditeur((String) obj.get("editeur".toString()));
                l.setDescription((String) obj.get("description".toString()));
                SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd");
                Date dateEdition = format.parse(obj.get("dateEdition").toString());
                l.setDate_edition(dateEdition);
                
                
            
                livres.add(l);
            }

        } catch (IOException ex) {
            System.out.println(ex.getMessage());
        }
        return livres;
    }

    public ArrayList<Livre> getAllLivres() {
        String url = Statics.BASE_URL + "/livre/AllLivres";
        req.setUrl(url);
        req.setPost(false);
        req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                try {
                    livres = parseLivres(new String(req.getResponseData()));
                } catch (ParseException ex) {
                    
                }
                req.removeResponseListener(this);
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        return livres;
    }
    
     public boolean  deleteLivre(int id){
       String url = Statics.BASE_URL + "/livre/deleteJSON/" +id;

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
