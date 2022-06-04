package com.example.logisticaliot;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.graphics.Color;
import android.location.Address;
import android.location.Geocoder;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.CountDownTimer;
import android.util.Log;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.Volley;
import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.OnMapReadyCallback;
import com.google.android.gms.maps.SupportMapFragment;
import com.google.android.gms.maps.model.BitmapDescriptor;
import com.google.android.gms.maps.model.BitmapDescriptorFactory;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.Marker;
import com.google.android.gms.maps.model.MarkerOptions;
import com.google.android.gms.maps.model.Polyline;
import com.google.android.gms.maps.model.PolylineOptions;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.ArrayList;
import java.util.Collections;
import java.util.HashMap;
import java.util.List;

public class MapActivity extends AppCompatActivity implements OnMapReadyCallback {
    GoogleMap mMap;

    Button DataBtn,ControlBtn;
    Marker marker;
    String TenTaiXe, DiaDiemXuatPhat, DiaDiemDichDen, Xuat, Dich;
    Double Kinhdo_hientai, Vido_hientai, Kinhdo, Vido, Kinhdo_xp, Vido_xp, Kinhdo_dd, Vido_dd;
    LatLng Xuatphat, Dichden;
    String URL;
    Geocoder geocoder;
    List<Address> addressList_Xuat, addressList_Dich;
    Polyline polyline = null;
    List<LatLng> LatLngList = new ArrayList<>();
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN, WindowManager.LayoutParams.FLAG_FULLSCREEN);
        setContentView(R.layout.activity_map);


        DataBtn = findViewById(R.id.Data_Btn);
        ControlBtn = findViewById(R.id.Control_Btn);

        URL= LoginActivity.GetDataLocation_Url;

        geocoder = new Geocoder(this);

        ControlBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent();
                intent.setClass(MapActivity.this, ControlActivity.class);
                startActivity(intent);
                overridePendingTransition(R.anim.slide_in_right, R.anim.slide_out_left);
            }
        });
        DataBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent();
                intent.setClass(MapActivity.this, DataActivity.class);
                startActivity(intent);
                overridePendingTransition(R.anim.slide_in_right, R.anim.slide_out_left);
            }
        });
        
        SupportMapFragment mapFragment = (SupportMapFragment) getSupportFragmentManager().findFragmentById(R.id.myMap);
        assert mapFragment != null;
        mapFragment.getMapAsync(this);
        
    }
    
    @Override
    public void onMapReady(GoogleMap map) {
//        A = 9.722166;
//        B = 105.649804;
        mMap = map;

        RequestQueue queue = Volley.newRequestQueue(this);
        JsonArrayRequest req = new JsonArrayRequest(Request.Method.GET, URL, null, new Response.Listener<JSONArray>() {
            @Override
            public void onResponse(JSONArray response) {
                // chuyen mang thanh chuoi
                for (int i=response.length() - 1; i>=0; i--) {
                    try {
                        JSONObject vitri = response.getJSONObject(i);

                        String preKinhdo = vitri.getString("Kinh do");
                        String preVido = vitri.getString("Vi do");

                        if ((preKinhdo.isEmpty())||(preVido.isEmpty())){
                            continue;
                        }else{
                            Kinhdo = Double.parseDouble(preKinhdo);
                            Vido = Double.parseDouble(preVido);
                            break;
                        }

                    } catch (JSONException e) {
                        e.printStackTrace();
                    }
                }
                for (int i = response.length() - 1; i>=0; i--) {
                    try {
                        JSONObject person = response.getJSONObject(i);

                        Xuat = person.getString("Xuat phat");
                        Dich = person.getString("Dich den");

                        if ((Xuat.isEmpty())||(Dich.isEmpty())){
                            continue;
                        }else{
                            try {
                                addressList_Xuat = geocoder.getFromLocationName(Xuat, 1);
                                addressList_Dich = geocoder.getFromLocationName(Dich, 1);
                                if(addressList_Xuat != null){
                                    Kinhdo_xp = Math.round(addressList_Xuat.get(0).getLatitude()*1000000.0)/1000000.0;
                                    Vido_xp = Math.round(addressList_Xuat.get(0).getLongitude()*1000000.0)/1000000.0;
                                }
                                if(addressList_Dich != null){
                                    Kinhdo_dd = Math.round(addressList_Dich.get(0).getLatitude()*1000000.0)/1000000.0;
                                    Vido_dd = Math.round(addressList_Dich.get(0).getLongitude()*1000000.0)/1000000.0;
                                }
                            } catch (Exception e) {
                                e.printStackTrace();
                            }
                            break;
                        }

                    } catch (JSONException e) {
                        e.printStackTrace();
                    }
                }

//                Toast.makeText(MapActivity.this,Kinhdo_xp.toString() +" "+Vido_xp.toString(), Toast.LENGTH_SHORT).show();
//                Toast.makeText(MapActivity.this,Kinhdo_dd.toString() +" "+Vido_dd.toString(), Toast.LENGTH_SHORT).show();
                Xuatphat = new LatLng(Kinhdo_xp, Vido_xp);
                Dichden = new LatLng(Kinhdo_dd, Vido_dd);

                LatLng xe = new LatLng(Kinhdo, Vido);
                mMap.moveCamera(CameraUpdateFactory.newLatLngZoom(xe, 14));

                if((Xuatphat!=null)&&(Dichden!=null)){
                    mMap.addMarker(new MarkerOptions().position(Xuatphat).title(Xuat));
                    mMap.addMarker(new MarkerOptions().position(Dichden).title(Dich));

                    String url = getMapsApiDirectionsUrl(Xuatphat, Dichden);

                    ReadTask downloadTask = new ReadTask();
                    // Start downloading json data from Google Directions API
                    downloadTask.execute(url);
                }
            }
                }, new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                }
            });
                queue.add(req);


        RequestQueue queue1 = Volley.newRequestQueue(this);
        RequestQueue queue2 = Volley.newRequestQueue(this);
        new CountDownTimer(1000000000,5000) {
            @Override
            public void onTick(long millisUntilFinished) {
                JsonArrayRequest req1 = new JsonArrayRequest(Request.Method.GET, URL, null, new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray response) {
                        // chuyen mang thanh chuoi
                        for (int i=response.length() - 1; i>=0; i--){
                            try{
                                JSONObject vitri = response.getJSONObject(i);

                                String preKinhdo = vitri.getString("Kinh do");
                                String preVido = vitri.getString("Vi do");

                                if ((preKinhdo.isEmpty())||(preVido.isEmpty())){
                                    continue;
                                }else{
                                    Kinhdo = Double.parseDouble(preKinhdo);
                                    Vido = Double.parseDouble(preVido);
                                    break;
                                }

                            } catch (JSONException e) {
                                e.printStackTrace();
                            }
                        }
                        for (int i=response.length() - 1; i>=0; i--){
                            try{
                                JSONObject person = response.getJSONObject(i);
                                TenTaiXe = person.getString("Ten");
                                DiaDiemXuatPhat = person.getString("Xuat phat");
                                DiaDiemDichDen = person.getString("Dich den");
                                //dua vao chuoi
                                if ((TenTaiXe.isEmpty())||(DiaDiemXuatPhat.isEmpty())||(DiaDiemDichDen.isEmpty())){
                                    continue;
                                }else{
                                    break;
                                }
                            } catch (JSONException e) {
                                e.printStackTrace();
                            }
                        }

                        if((Kinhdo!=null)&&(Vido!=null)) {
                            LatLng xe_hientai = new LatLng(Kinhdo, Vido);

                            if (marker != null) {
                                Kinhdo_hientai = marker.getPosition().latitude;
                                Vido_hientai = marker.getPosition().longitude;
                                if ((!Kinhdo_hientai.equals(Kinhdo)) || (!Vido_hientai.equals(Vido))) {
                                    marker.remove();
                                    marker = mMap.addMarker(new MarkerOptions()
                                            .title("Xuất phát từ " + DiaDiemXuatPhat + " đến " + DiaDiemDichDen)
                                            .snippet("Tên tài xế: " + TenTaiXe)
                                            .position(xe_hientai)
                                            .icon(BitmapDescriptorFactory.defaultMarker(BitmapDescriptorFactory.HUE_AZURE))
                                    );
                                }
                            } else {
                                marker = mMap.addMarker(new MarkerOptions()
                                        .title("Xuất phát từ " + DiaDiemXuatPhat + " đến " + DiaDiemDichDen)
                                        .snippet("Tên tài xế: " + TenTaiXe)
                                        .position(xe_hientai)
                                        .icon(BitmapDescriptorFactory.defaultMarker(BitmapDescriptorFactory.HUE_AZURE))
                                );
                            }
                        }
                    }
                }, new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                    }
                });
                queue1.add(req1);

                JsonArrayRequest req2 = new JsonArrayRequest(Request.Method.GET, URL, null, new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray response) {
                        // chuyen mang thanh chuoi
                        for (int i=response.length() - 1; i>=0; i--) {
                            try {
                                JSONObject vitri = response.getJSONObject(i);

                                String prKinhdo = vitri.getString("Kinh do");
                                String prVido = vitri.getString("Vi do");
                                double Ki = Double.parseDouble(prKinhdo);
                                double Vi = Double.parseDouble(prVido);

                                LatLng Loca = new LatLng(Ki, Vi);

                                if((Ki==0.000000)&&(Vi==0.000000)){
                                    continue;
                                } else{
                                    LatLngList.add(Loca);
                                    PolylineOptions polylineOption = new PolylineOptions()
                                            .addAll(LatLngList).clickable(true);
                                    polylineOption.width(4);
                                    polylineOption.color(Color.BLUE);
                                    polyline = map.addPolyline(polylineOption);
                                }

                                if ((prKinhdo.isEmpty())||(prVido.isEmpty())){
//                                    continue;
//                                }else{
//                                    Kinhdo = Double.parseDouble(preKinhdo);
//                                    Vido = Double.parseDouble(preVido);
                                    break;
                                }

                            } catch (JSONException e) {
                                e.printStackTrace();
                            }
                        }
                        LatLngList.clear();
                    }

                }, new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                    }
                });
                queue2.add(req2);
            }
            @Override
            public void onFinish() {
            }
        }.start();

    }

    private String  getMapsApiDirectionsUrl(LatLng origin,LatLng dest) {
        // Origin of route
        String str_origin = "origin="+origin.latitude+","+origin.longitude;
        // Destination of route
        String str_dest = "destination="+dest.latitude+","+dest.longitude;
        // Sensor enabled
        String sensor = "sensor=false";
        // Building the parameters to the web service
        String parameters = str_origin+"&"+str_dest+"&"+sensor;
        // Output format
        String output = "json";
        // Building the url to the web service
        String url = "https://maps.googleapis.com/maps/api/directions/"+output+"?"+parameters+ "&key=" + getString(R.string.map_api_key);
        return url;
    }

    private class ReadTask extends AsyncTask<String, Void , String> {

        @Override
        protected String doInBackground(String... url) {
            // TODO Auto-generated method stub
            String data = "";
            try {
                MapHttpConnection http = new MapHttpConnection();
                data = http.readUr(url[0]);


            } catch (Exception e) {
                // TODO: handle exception
                Log.d("Background Task", e.toString());
            }
            return data;
        }

        @Override
        protected void onPostExecute(String result) {
            super.onPostExecute(result);
            new ParserTask().execute(result);
        }
    }

    public class MapHttpConnection {
        public String readUr(String mapsApiDirectionsUrl) throws IOException {
            String data = "";
            InputStream istream = null;
            HttpURLConnection urlConnection = null;
            try {
                java.net.URL url = new URL(mapsApiDirectionsUrl);
                urlConnection = (HttpURLConnection) url.openConnection();
                urlConnection.connect();
                istream = urlConnection.getInputStream();
                BufferedReader br = new BufferedReader(new InputStreamReader(istream));
                StringBuffer sb = new StringBuffer();
                String line ="";
                while ((line = br.readLine()) != null) {
                    sb.append(line);
                }
                data = sb.toString();
                br.close();
            }
            catch (Exception e) {
                Log.d("Exception while reading url", e.toString());
            } finally {
                istream.close();
                urlConnection.disconnect();
            }
            return data;
        }
    }

    public class PathJSONParser {
        public List<List<HashMap<String, String>>> parse(JSONObject jObject) {
            List<List<HashMap<String, String>>> routes = new ArrayList<List<HashMap<String,String>>>();
            JSONArray jRoutes = null;
            JSONArray jLegs = null;
            JSONArray jSteps = null;
            try {
                jRoutes = jObject.getJSONArray("routes");
                for (int i=0 ; i < jRoutes.length() ; i ++) {
                    jLegs = ((JSONObject) jRoutes.get(i)).getJSONArray("legs");
                    List<HashMap<String, String>> path = new ArrayList<HashMap<String,String>>();
                    for(int j = 0 ; j < jLegs.length() ; j++) {
                        jSteps = ((JSONObject) jLegs.get(j)).getJSONArray("steps");
                        for(int k = 0 ; k < jSteps.length() ; k ++) {
                            String polyline = "";
                            polyline = (String) ((JSONObject) ((JSONObject) jSteps.get(k)).get("polyline")).get("points");
                            List<LatLng> list = decodePoly(polyline);
                            for(int l = 0 ; l < list.size() ; l ++){
                                HashMap<String, String> hm = new HashMap<String, String>();
                                hm.put("lat",
                                        Double.toString(((LatLng) list.get(l)).latitude));
                                hm.put("lng",
                                        Double.toString(((LatLng) list.get(l)).longitude));
                                path.add(hm);
                            }
                        }
                        routes.add(path);
                    }
                }
            } catch (Exception e) {
                e.printStackTrace();
            }
            return routes;

        }

        private List<LatLng> decodePoly(String encoded) {
            List<LatLng> poly = new ArrayList<LatLng>();
            int index = 0, len = encoded.length();
            int lat = 0, lng = 0;

            while (index < len) {
                int b, shift = 0, result = 0;
                do {
                    b = encoded.charAt(index++) - 63;
                    result |= (b & 0x1f) << shift;
                    shift += 5;
                } while (b >= 0x20);
                int dlat = ((result & 1) != 0 ? ~(result >> 1) : (result >> 1));
                lat += dlat;

                shift = 0;
                result = 0;
                do {
                    b = encoded.charAt(index++) - 63;
                    result |= (b & 0x1f) << shift;
                    shift += 5;
                } while (b >= 0x20);
                int dlng = ((result & 1) != 0 ? ~(result >> 1) : (result >> 1));
                lng += dlng;

                LatLng p = new LatLng((((double) lat / 1E5)),
                        (((double) lng / 1E5)));
                poly.add(p);
            }
            return poly;
        }}

    private class ParserTask extends AsyncTask<String,Integer, List<List<HashMap<String , String >>>> {
        @Override
        protected List<List<HashMap<String, String>>> doInBackground(
                String... jsonData) {
            // TODO Auto-generated method stub
            JSONObject jObject;
            List<List<HashMap<String, String>>> routes = null;
            try {
                jObject = new JSONObject(jsonData[0]);
                PathJSONParser parser = new PathJSONParser();
                routes = parser.parse(jObject);


            } catch (Exception e) {
                e.printStackTrace();
            }
            return routes;
        }

        @Override
        protected void onPostExecute(List<List<HashMap<String, String>>> routes) {
            ArrayList<LatLng> points = null;
            PolylineOptions polyLineOptions = null;

            // traversing through routes
            for (int i = 0; i < routes.size(); i++) {
                points = new ArrayList<LatLng>();
                polyLineOptions = new PolylineOptions();
                List<HashMap<String, String>> path = routes.get(i);

                for (int j = 0; j < path.size(); j++) {
                    HashMap<String, String> point = path.get(j);

                    double lat = Double.parseDouble(point.get("lat"));
                    double lng = Double.parseDouble(point.get("lng"));
                    LatLng position = new LatLng(lat, lng);

                    points.add(position);
                }

                polyLineOptions.addAll(points);
                polyLineOptions.width(4);
                polyLineOptions.color(Color.RED);
            }
            if(polyLineOptions!=null) {
                mMap.addPolyline(polyLineOptions);
            }
        }}
}