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
import com.mycompany.myapp.entities.Livre;
import com.mycompany.myapp.entities.Theme;
import com.mycompany.myapp.utils.Statics;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.Map;

/**
 *
 * @author Pc Anis
 */
public class ServiceTheme {
    public ArrayList<Theme> themes;

    public static ServiceTheme instance = null;
    public boolean resultOK;
    private ConnectionRequest req;

    public ServiceTheme() {
        req = new ConnectionRequest();
    }

    public static ServiceTheme getInstance()
    {
        if(instance==null)
        {
            instance = new ServiceTheme();
        }
        return instance ;
    }


    public boolean addTheme(Theme t) {

        String nom = t.getNom();
        String description = t.getDescription();
       
        

        String url = Statics.BASE_URL + "/theme/addJSON?nom=" + t.getNom() +"&description=" + t.getDescription() ;
 
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
    
    
    public void updateTheme(Theme theme) {
        String url = Statics.BASE_URL + "/theme/editJSON/" + theme.getIdtheme()+"?nomTheme="+theme.getNom() +"&description=" + theme.getDescription() ;
        req.setUrl(url);
        req.setPost(false);
       
        req.addArgument("idTheme", String.valueOf(theme.getIdtheme()));
        req.addArgument("nomTheme", theme.getNom());
        req.addArgument("description", theme.getDescription());

        req.addResponseListener((NetworkEvent evt) -> {
            byte[] data = (byte[]) req.getResponseData();
            String s = new String(data);
            System.out.println("Result: " + s);
        });

        NetworkManager.getInstance().addToQueue(req);
    }

    public ArrayList<Theme> parseThemes(String jsonText) throws ParseException {
        try {
            themes = new ArrayList<>();
            JSONParser j = new JSONParser();
            Map<String, Object> themesListJson
                    = j.parseJSON(new CharArrayReader(jsonText.toCharArray()));

            List<Map<String, Object>> list = (List<Map<String, Object>>) themesListJson.get("root");
            for (Map<String, Object> obj : list) {
                Theme t = new Theme();
                 float id = Float.parseFloat(obj.get("idTheme").toString());
                t.setIdtheme(Math.round(id));
                t.setNom((String) obj.get("nomTheme".toString()));
                t.setDescription((String) obj.get("description".toString()));
                
                
            
                themes.add(t);
            }

        } catch (IOException ex) {
            System.out.println(ex.getMessage());
        } 
        return themes;
    }

    public ArrayList<Theme> getAllThemes() {
        String url = Statics.BASE_URL + "/theme/AllThemes";
        req.setUrl(url);
        req.setPost(false);
        req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                try {
                    themes = parseThemes(new String(req.getResponseData()));
                } catch (ParseException ex) {
                    
                }
                req.removeResponseListener(this);
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        return themes;
    }
    
     public boolean  deleteTheme(int id){
       String url = Statics.BASE_URL + "/theme/deleteJSON/" +id;

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
