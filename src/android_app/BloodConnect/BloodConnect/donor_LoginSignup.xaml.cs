using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

using Xamarin.Forms;
using Xamarin.Forms.Xaml;
using System.Net.Http;
using Newtonsoft.Json;


namespace BloodConnect
{
	[XamlCompilation(XamlCompilationOptions.Compile)]
	public partial class donor_LoginSignup : ContentPage
	{
        private HttpClient _client = new HttpClient();
        private string errorMessage;
        private bool loginStatus;

        public donor_LoginSignup ()
		{
			InitializeComponent ();

            ic_userloginEmail.Source = MainPage.primaryDomain + "/images/ic_email.png";
            ic_userloginPassword.Source = MainPage.primaryDomain + "/images/ic_password.png";
            //ic_userSignupEmail.Source = MainPage.primaryDomain + "/images/ic_email.png";
            //ic_userSignupName.Source = MainPage.primaryDomain + "/images/ic_account.png";
            //ic_userSignupPhone.Source = MainPage.primaryDomain + "/images/ic_phone.png";
            //ic_userSignupIdentification.Source = MainPage.primaryDomain + "/images/ic_perm_identity.png";
            ic_userSignupPassword.Source = MainPage.primaryDomain + "/images/ic_password.png";

            user_loginEmailID.Text = "kanojia24.10@gmail.com";
            user_loginPassword.Text = "lol";
        }

        void user_LogintoSignup(Object sender, System.EventArgs e)
        {
            DisplayAlert("Title", "Clicked you", "Ok");
            //await Navigation.PushAsync(new userAuthority_LoginSignup());
            //if (grid_UserSignupLayout.IsVisible)
            //{
            //    grid_UserLoginLayout.IsVisible = true;
            //    grid_UserSignupLayout.IsVisible = false;
            //}
            //else
            //{
            //    grid_UserLoginLayout.IsVisible = false;
            //    grid_UserSignupLayout.IsVisible = true;
            //}

        }


        void user_LoginSignupClicked(Object sender, EventArgs e)
        {
            DisplayAlert("Title", "Clicked you", "Ok");
            //grid_UserLoginLayout.Isvisible = false;
            //await Navigation.PushAsync(new userAuthority_LoginSignup());
        }

        async void user_LoginClicked(Object sender, EventArgs e)
        {
            //DisplayAlert("Title", "Clicked you", "Ok");
            //grid_UserLoginLayout.Isvisible = false;
            //await Navigation.PushAsync(new user_MainScreen());

            if (String.IsNullOrEmpty(user_loginEmailID.Text) | String.IsNullOrEmpty(user_loginPassword.Text))
            {
                await DisplayAlert("Empty Fields", "Fields Cannot Be Empty.", "Ok");
            }
            else
            {
                await Task.Run(() => Login());


                if (this.loginStatus)
                {
                    // Upon successful sign up, we move forward to next screen!
                    user_loginEmailID.Text = "";
                    user_loginPassword.Text = "";
                    if (models.LoggedInUserData.u_account_status == "1")
                    {
                        await DisplayAlert("Blocked Account", "Your Account Has Been Blocked!", "OK");
                        return;
                    }
                    else if (models.LoggedInUserData.u_account_status == "2")
                    {
                        await DisplayAlert("Not Verified", "You Need To Verify Your Email Address First!", "OK");
                        return;
                    }
                    else if (models.LoggedInUserData.u_account_status == "0")
                        await Navigation.PushAsync(new user_MainScreen());
                    else
                    {
                        await DisplayAlert("OOps", "Some Error Occurred", "OK");
                        return;
                    }
                }
                else
                {
                    await DisplayAlert("Bad Login", this.errorMessage, "Ok");
                }
            }
        }

        private async Task Login()
        {
            try
            {
                var data_to_send = new FormUrlEncodedContent(new[]
                {
                    new KeyValuePair<string, string>("user_email", user_loginEmailID.Text),
                    new KeyValuePair<string, string>("user_password", user_loginPassword.Text)
                });
                var content = await _client.PostAsync(MainPage.primaryDomain + "/BloodConnect/users/registration/login.php", data_to_send);

                var temp_response = await content.Content.ReadAsStringAsync();

                try
                {
                    dynamic obj2 = Newtonsoft.Json.Linq.JObject.Parse(temp_response);
                    if (Convert.ToString(obj2.error_code) == "1")
                    {
                        this.errorMessage = obj2.message;
                        this.loginStatus = false; // Bad Input?
                    }
                    else
                    {
                        this.loginStatus = true; // Flag to tell the other methods about Sign up status
                        models.LoggedInUserData.u_id = Convert.ToString(obj2.u_id);
                        models.LoggedInUserData.u_name = Convert.ToString(obj2.u_name);
                        models.LoggedInUserData.u_email = Convert.ToString(obj2.u_email);
                        models.LoggedInUserData.u_dob = Convert.ToString(obj2.u_dob);
                        models.LoggedInUserData.u_age = Convert.ToString(obj2.u_age);
                        models.LoggedInUserData.u_blood_type = Convert.ToString(obj2.u_blood_type);
                        models.LoggedInUserData.u_medical_issues_flag = Convert.ToString(obj2.u_medical_issues_flag);
                        models.LoggedInUserData.u_medical_issues = Convert.ToString(obj2.u_medical_issues);
                        models.LoggedInUserData.u_UIDAI = Convert.ToString(obj2.u_UIDAI);
                        models.LoggedInUserData.u_address = Convert.ToString(obj2.u_address);
                        models.LoggedInUserData.u_state = Convert.ToString(obj2.u_state);
                        models.LoggedInUserData.u_country = Convert.ToString(obj2.u_country);
                        models.LoggedInUserData.u_phone_number = Convert.ToString(obj2.u_phone_number);
                        models.LoggedInUserData.u_account_status = Convert.ToString(obj2.u_account_status);
                        models.LoggedInUserData.u_qr_link = Convert.ToString(obj2.u_qr_link);
                        models.LoggedInUserData.u_internal_hash = Convert.ToString(obj2.u_internal_hash);
                    }

                }
                catch (Exception)
                {
                    await DisplayAlert("No Internet", "Could Not Connect To Internet", "Ok");
                }
            }
            catch (Exception)
            {
                await DisplayAlert("No Internet", "Could Not Connect To Internet", "Ok");
            }
        }
    }
}