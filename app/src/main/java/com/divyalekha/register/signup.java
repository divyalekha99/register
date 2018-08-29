package com.divyalekha.register;

import android.app.ProgressDialog;
import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.divyalekha.register.AppController;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.util.HashMap;
import java.util.Map;

public class signup extends AppCompatActivity {

    private static final String TAG = signup.class.getSimpleName();
    private EditText name, email, mobile, pass, repass, clg;
    TextView loginLink;
    private Button createAccount;
    private int flag = 0;
    private String namet;
    private ProgressDialog progressDialog;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_signup);

        name = (EditText) findViewById(R.id.name);
        email = (EditText) findViewById(R.id.email);
        mobile = (EditText) findViewById(R.id.mobile);
        pass = (EditText) findViewById(R.id.pass);
        //   repass = (EditText) findViewById(R.id.input_reEnterPassword);
        clg = (EditText) findViewById(R.id.clg);
        loginLink = (TextView) findViewById(R.id.link_login);
        createAccount = (Button) findViewById(R.id.signup);
        progressDialog = new ProgressDialog(this);
        progressDialog.setCancelable(false);

        // ShimmerFrameLayout shimmerFrameLayout = (ShimmerFrameLayout) findViewById(R.id.shimmer);
        // shimmerFrameLayout.startShimmerAnimation();

        try {
            if (!isConnected()) {
                Toast.makeText(this, "Please connect to the Internet.", Toast.LENGTH_SHORT).show();
            }
        } catch (InterruptedException f) {

        } catch (IOException e) {

        }

        createAccount.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
               signup();
            }
        });

        loginLink.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(getApplicationContext(), MainActivity.class);
                startActivity(intent);
                finish();
                // overridePendingTransition(R.animator.push_left_in, R.animator.push_left_out);
            }
        });
    }

    public boolean isConnected() throws InterruptedException, IOException {
        String command = "ping -c 1 google.com";
        return (Runtime.getRuntime().exec(command).waitFor() == 0);
    }


    public void signup() {
        if (!validate()) {
            onSignupFailed();
            return;
        }


        namet= name.getText().toString().trim();
        final String emailt = email.getText().toString().trim();
        final String mobilet = mobile.getText().toString().trim();
        String password = pass.getText().toString().trim();
        String clgn = clg.getText().toString().trim();

        registerUser(namet, emailt, password, mobilet, clgn);

    }


    public void onSignupFailed() {
        Toast.makeText(getBaseContext(), "Signup failed", Toast.LENGTH_LONG).show();
        createAccount.setEnabled(true);
    }

    @Override
    public void onBackPressed() {
        Intent i = new Intent(signup.this, MainActivity.class);
        startActivity(i);
    }

    private void registerUser(final String name, final String email,
                              final String password, final String mobile, final String collegeName) {
        String tag_string_req = "req_register";

        progressDialog.setMessage("Creating Account ...");
        showDialog();

        StringRequest strReq = new StringRequest(Request.Method.POST,
                "https://192.168.1.7:3306/and_con/register.php", new Response.Listener<String>() {

            @Override
            public void onResponse(String response) {
                Log.d(TAG, "Register Response: " + response.toString());
                hideDialog();

                try {
                    JSONObject jObj = new JSONObject(response);
                    boolean error = jObj.getBoolean("error");

                    if (!error) {

                        JSONObject user = jObj.getJSONObject("user");
                        String name = user.getString("name");
                        String email = user.getString("email");
                        String password = user.getString("password");
                        String mobile = user.getString("mobileNo");
                        String collegeName = user.getString("college");

                        Toast.makeText(getApplicationContext(), "Registration successful!", Toast.LENGTH_LONG).show();

                        Intent i = new Intent(signup.this, MainActivity.class);
                        i.putExtra("username", name);
                        i.putExtra("usermail", email);
                        i.putExtra("usernum", mobile);
                        startActivity(i);
                        finish();
                    } else {


                        String errorMsg = jObj.getString("error_msg");
                        Toast.makeText(getApplicationContext(),
                                errorMsg, Toast.LENGTH_LONG).show();
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                }

            }
        }, new Response.ErrorListener() {

            @Override
            public void onErrorResponse(VolleyError error) {
                Log.e(TAG, "Registration Error: " + error.getMessage());
                Toast.makeText(getApplicationContext(),
                        error.getMessage(), Toast.LENGTH_LONG).show();
                hideDialog();
            }
        }) {

            @Override
            protected Map<String, String> getParams() {
                Map<String, String> params = new HashMap<String, String>();
                params.put("name", name);
                params.put("email", email);
                params.put("password",password);
                params.put("mobileNo", mobile);
                params.put("college", collegeName);

                return params;
            }

        };

        AppController.getInstance().addToRequestQueue(strReq, tag_string_req);
    }

    private void showDialog() {
        if (!progressDialog.isShowing())
            progressDialog.show();
    }

    private void hideDialog() {
        if (progressDialog.isShowing())
            progressDialog.dismiss();
    }


    public boolean validate() {
        boolean valid = true;

        String nametext = name.getText().toString();
        String emailtext = email.getText().toString();
        String mobiletext = mobile.getText().toString();
        String passtext = pass.getText().toString();
        //String reEnterPassword = rePasText.getText().toString();


        if (nametext.isEmpty() || nametext.length() < 3) {
            name.setError("at least 3 characters");
            valid = false;
        } else {
            name.setError(null);
        }

        if (emailtext.isEmpty() || !android.util.Patterns.EMAIL_ADDRESS.matcher(emailtext).matches()) {
            email.setError("enter a valid email address");
            valid = false;
        } else {
            email.setError(null);
        }

        if (mobiletext.isEmpty() || mobiletext.length() != 10) {
            mobile.setError("Enter Valid Mobile Number");
            valid = false;
        } else {
            mobile.setError(null);
        }

        if (passtext.isEmpty() || passtext.length() < 4 || passtext.length() > 10) {
            pass.setError("between 4 and 10 alphanumeric characters");
            valid = false;
        } else {
            pass.setError(null);
        }

  /*          if (reEnterPassword.isEmpty() || reEnterPassword.length() < 4 || reEnterPassword.length() > 10 || !(reEnterPassword.equals(password))) {
                rePasText.setError("Password Do not match");
                valid = false;
            } else {
                rePasText.setError(null);
            }
*/
        return valid;
    }
}
