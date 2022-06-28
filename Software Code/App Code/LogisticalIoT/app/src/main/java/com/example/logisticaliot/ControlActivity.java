package com.example.logisticaliot;

import android.content.Intent;
import android.graphics.Color;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.CountDownTimer;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.example.logisticaliot.GetDataVolley.GetInformation;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.URL;
import java.net.URLConnection;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

public class ControlActivity extends AppCompatActivity {
    Button MapBtn, DataBtn, OnBtn, OffBtn, AutoBtn;
    TextView MemoView;
    EditText InputValue;
    ImageView AirConditionerImg;
    String Mode, Setup_Mod;
    Spinner Spinner;
    Integer LockPosition;

    int[] img = {R.drawable.airconditioneropen, R.drawable.airconditionerclose, R.drawable.auto};
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN, WindowManager.LayoutParams.FLAG_FULLSCREEN);
        setContentView(R.layout.activity_control);

        MapBtn = findViewById(R.id.Map_Btn);
        DataBtn = findViewById(R.id.Data_Btn);
        OnBtn = findViewById(R.id.On_Btn);
        OffBtn = findViewById(R.id.Off_Btn);
        AutoBtn = findViewById(R.id.Ok_Btn);
        InputValue = findViewById(R.id.InputValue);
        AirConditionerImg = findViewById(R.id.AirConditioner_Img);
        MemoView = findViewById(R.id.Memo_Textview);
        Spinner = (Spinner)findViewById(R.id.spinner);

        ArrayList<String> arraySensor = new ArrayList<String>();
        arraySensor.add("Nhiệt độ");
        arraySensor.add("Độ ẩm");

        ArrayAdapter arrayAdapter = new ArrayAdapter(this, android.R.layout.simple_spinner_item, arraySensor);
        arrayAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        Spinner.setAdapter(arrayAdapter);
        Spinner.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
            @Override
            public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
                LockPosition = position;
                new CountDownTimer(1000000000,5000) {
                    @Override
                    public void onTick(long millisUntilFinished) {
                        switch (LockPosition){
                            case 0:
                            case 1:
                                runOnUiThread(new Runnable() {
                                    @Override
                                    public void run() {
                                        new docJson().execute(LoginActivity.ControlSetup_Url);
                                    }
                                });
                                break;
                            default:
                                break;
                        }
                    }
                    @Override
                    public void onFinish() {

                    }
                }.start();
            }

            @Override
            public void onNothingSelected(AdapterView<?> parent) {

            }
        });

        OnBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                OnBtn.setBackgroundColor(Color.BLUE);
                OffBtn.setBackgroundColor(Color.GRAY);
                AutoBtn.setBackgroundColor(Color.GRAY);
                Mode = "ON";
                AirConditionerImg.setImageResource(img[0]);
//                    Toast.makeText(ControlActivity.this, "Đã mở thiết bị", Toast.LENGTH_SHORT).show();
                PushControl(Mode);
                MemoView.setText("Chưa thiết lập giá trị !!!");
            }
        });

        OffBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                OnBtn.setBackgroundColor(Color.GRAY);
                OffBtn.setBackgroundColor(Color.BLUE);
                AutoBtn.setBackgroundColor(Color.GRAY);
                Mode = "OFF";
                AirConditionerImg.setImageResource(img[1]);
//                    Toast.makeText(ControlActivity.this, "Đã tắt thiết bị", Toast.LENGTH_SHORT).show();
                PushControl(Mode);
                MemoView.setText("Chưa thiết lập giá trị !!!");
            }
        });

        AutoBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Mode = InputValue.getText().toString();
                if(Mode.isEmpty()){
                    Toast.makeText(ControlActivity.this, "Chưa nhập giá trị !!!", Toast.LENGTH_SHORT).show();
                }else {
                    Toast.makeText(ControlActivity.this, "Đã cập nhật", Toast.LENGTH_SHORT).show();
                    MemoView.setText("Giá trị đã thiết lặp: "+Mode+" *C");
                    OnBtn.setBackgroundColor(Color.GRAY);
                    OffBtn.setBackgroundColor(Color.GRAY);
                    AutoBtn.setBackgroundColor(Color.BLUE);
                    AirConditionerImg.setImageResource(img[2]);
                    PushControl(Mode);
                }
            }
        });

        MapBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent();
                intent.setClass(ControlActivity.this, MapActivity.class);
                startActivity(intent);
                overridePendingTransition(R.anim.slide_in_left, R.anim.slide_out_right);
            }
        });
        DataBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent();
                intent.setClass(ControlActivity.this, DataActivity.class);
                startActivity(intent);
                overridePendingTransition(R.anim.slide_in_left, R.anim.slide_out_right);
            }
        });
    }

    private void PushControl(String Mod){
        StringRequest request = new StringRequest(Request.Method.POST, LoginActivity.Control_Url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        System.out.println(response.toString());
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {

            }
        }){
            @Nullable
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<>();
//                params.put("Auto",Aut);
                if(LockPosition==0){
                    params.put("Type","ND");
                    params.put("Mode_Temp",Mod);
                } else if(LockPosition==1){
                    params.put("Type","DA");
                    params.put("Mode_Humi",Mod);
                }

                return params;
            }
        };
        RequestQueue queue = Volley.newRequestQueue(ControlActivity.this);
        queue.add(request);
    }

    class docJson extends AsyncTask<String, Integer, String>
    {
        @Override
        protected String doInBackground(String... params) {
            return readJson(params[0]);
        }

        @Override
        protected void onPostExecute(String s) {
            try {
                JSONObject root = new JSONObject(s);
                if(LockPosition==0) {
                    Setup_Mod = root.getString("Nhiet do");
                } else if(LockPosition==1){
                    Setup_Mod = root.getString("Do am");
                }
//                Toast.makeText(ControlActivity.this,Setup_Han, Toast.LENGTH_SHORT).show();
                if (Setup_Mod.equals("OFF")) {
                    OnBtn.setBackgroundColor(Color.GRAY);
                    OffBtn.setBackgroundColor(Color.BLUE);
                    AutoBtn.setBackgroundColor(Color.GRAY);
                    AirConditionerImg.setImageResource(img[1]);
                    MemoView.setText("Chưa thiết lập giá trị !!!");
                } else if(Setup_Mod.equals("ON")){
                    OnBtn.setBackgroundColor(Color.BLUE);
                    OffBtn.setBackgroundColor(Color.GRAY);
                    AutoBtn.setBackgroundColor(Color.GRAY);
                    AirConditionerImg.setImageResource(img[0]);
                    MemoView.setText("Chưa thiết lập giá trị !!!");
                } else{
                    MemoView.setText("Giá trị đã thiết lặp: "+Setup_Mod+" *C");
                    OnBtn.setBackgroundColor(Color.GRAY);
                    OffBtn.setBackgroundColor(Color.GRAY);
                    AutoBtn.setBackgroundColor(Color.BLUE);
                    AirConditionerImg.setImageResource(img[2]);
                }
            } catch (JSONException e) {
                e.printStackTrace();
            }
        }
    }

    private static String readJson(String theUrl) {
        StringBuilder content = new StringBuilder();

        try {
            // create a url object
            URL url = new URL(theUrl);
            // create a urlconnection object
            URLConnection urlConnection = url.openConnection();
            // wrap the urlconnection in a bufferedreader
            BufferedReader bufferedReader = new BufferedReader(new InputStreamReader(urlConnection.getInputStream()));
            String line;
            // read from the urlconnection via the bufferedreader
            while ((line = bufferedReader.readLine()) != null) {
                content.append(line + "\n");
                bufferedReader.close();
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
        return content.toString();
    }

}