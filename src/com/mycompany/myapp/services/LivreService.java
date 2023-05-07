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
import com.mycompany.myapp.entities.Promo;

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
        String langue = l.getLangue();
        float prix = l.getPrix();
        

        String url = Statics.BASE_URL + "/livre/addJSON?libelle=" + libelle + "&categorie=" + categorie + "&description=" + description + "&editeur=" 
                + editeur + "&prix=" + prix + "&langue=" + langue;
 
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
    
    
    public void updateLivre(Livre livre) {
        String url = Statics.BASE_URL + "/livre/editJSON/" + livre.getId()+"?libelle="+livre.getLibelle() +"&description=" + livre.getDescription() 
                +"&editeur="+ livre.getEditeur() +"&categorie=" + livre.getCategorie() + "&prix=" + livre.getPrix() + "&langue=" + livre.getLangue();
        req.setUrl(url);
        req.setPost(false);
       
        req.addArgument("id", String.valueOf(livre.getId()));
        req.addArgument("libelle", livre.getLibelle());
        req.addArgument("prix", String.valueOf(livre.getPrix()));
        req.addArgument("description", livre.getDescription());
        req.addArgument("categorie", livre.getCategorie());
        req.addArgument("langue", livre.getLangue());
        req.addArgument("editeur", livre.getEditeur());

        req.addResponseListener((NetworkEvent evt) -> {
            byte[] data = (byte[]) req.getResponseData();
            String s = new String(data);
            System.out.println("Result: " + s);
        });

        NetworkManager.getInstance().addToQueue(req);
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
                l.setId((int) id);
                l.setLibelle((String) obj.get("libelle".toString()));
                l.setPrix(Float.parseFloat(obj.get("prix").toString()));
                l.setCategorie((String) obj.get("categorie".toString()));
                l.setEditeur((String) obj.get("editeur".toString()));
                l.setDescription((String) obj.get("description".toString()));
                l.setLangue((String)obj.get("langue".toString()));
                
            
                livres.add(l);
            }

        } catch (IOException ex) {
            System.out.println(ex.getMessage());
        } 
        return livres;
    }

    public ArrayList<Livre> getAllLivres() {
        String url = Statics.BASE_URL + "/livre/json";
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