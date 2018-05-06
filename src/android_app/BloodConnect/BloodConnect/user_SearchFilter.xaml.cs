using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Net.Http;
using System.Text;
using System.Threading;
using System.Threading.Tasks;

using Xamarin.Forms;
using Xamarin.Forms.Xaml;

namespace BloodConnect
{
	[XamlCompilation(XamlCompilationOptions.Compile)]
	public partial class user_SearchFilter : ContentPage
	{
        private HttpClient _client = new HttpClient();
        List<string> listOfStates;
        private string ListFetchError;
        private bool ListFetchStatus;

        protected override void OnAppearing()
        {
            //await Task.Run(() => StateListFetcher());
            search_State.Items.Add("Select State");
            search_State.Items.Add("New Delhi");
            search_State.Items.Add("Mumbai");
        }

        public user_SearchFilter ()
		{
			InitializeComponent ();
            
            //search_RequiredUnits.Text = "02";
            //SelectBloodGroup_Label - Blood Group Text
            //SelectStatePicker_Label - State Text            
        }

        private void search_SearchBackClicked(object sender, EventArgs e)
        {
            Navigation.PopModalAsync();
        }



        private void search_State_SelectedIndexChanged(object sender, EventArgs e)
        {
            SelectStatePicker_Label.Text = search_State.SelectedItem.ToString();
        }

        private void searchStatePicker_Activated(object sender, EventArgs e)
        {
            search_State.Focus();
        }

        private void search_BloodGroup_SelectedIndexChanged(object sender, EventArgs e)
        {
            SelectBloodGroup_Label.Text = search_BloodGroup.SelectedItem.ToString();
        }

        private void searchBloodGroupPicker_Activated(object sender, EventArgs e)
        {
            search_BloodGroup.Focus();
        }

        private async void search_SearchCompleteClicked(object sender, EventArgs e)
        {
            //DisplayAlert("Search Done", "Done Search", "Ok");
            if (Convert.ToInt64(search_RequiredUnits.Text) > 5)
            {
                await DisplayAlert("Check Units Again", "Unit of bloods cannot be more than 5", "ok");
            }
            else
            {
                models.GlobalProps.search_bloodType = search_BloodGroup.SelectedItem.ToString();
                models.GlobalProps.search_state = search_State.SelectedItem.ToString();
                models.GlobalProps.search_country = models.LoggedInUserData.u_country;
                models.GlobalProps.search_donation_amount = search_RequiredUnits.Text;
                //await Task.Run(() => BankListFetcher());
                await Navigation.PopModalAsync();
            }
        }

        private async Task StateListFetcher()
        {
            try
            {
                var content = await _client.GetAsync(MainPage.primaryDomain + "/BloodConnect/list_of_states.php?user_country=India");

                var temp_response = await content.Content.ReadAsStringAsync();
                Console.WriteLine("Temp Response : " + temp_response);

                try
                {
                    if (!string.IsNullOrEmpty(temp_response))
                    {
                        listOfStates = temp_response.Split(',').ToList();

                        //search_State.Items.Clear();

                        foreach (var item in listOfStates)
                        {
                            Console.WriteLine("Item : " + item);
                            
                        }

                        dynamic dynObj = JsonConvert.DeserializeObject(Convert.ToString(temp_response));
                        search_State.Items.Clear();
                        search_State.Items.Add("Select A Country");
                        foreach (var data in dynObj)
                        {
                            Console.WriteLine("Data : " + data);
                            //search_State.Items.Add((string) data);
                        }
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

        //private async Task BankListFetcher()
        //{
        //    try
        //    {
        //        var data_to_send = new FormUrlEncodedContent(new[]
        //        {
        //            new KeyValuePair<string, string>("blood_type", search_BloodGroup.SelectedItem.ToString()),
        //            new KeyValuePair<string, string>("state", search_State.SelectedItem.ToString()),
        //            new KeyValuePair<string, string>("country", models.LoggedInUserData.u_country),
        //            new KeyValuePair<string, string>("donation_amount", search_RequiredUnits.Text)
        //        });
        //        var content = await _client.PostAsync(MainPage.primaryDomain + "/BloodConnect/blood_bank/list_of_banks.php", data_to_send);

        //        var temp_response = await content.Content.ReadAsStringAsync();

        //        try
        //        {
        //            dynamic obj2 = Newtonsoft.Json.Linq.JObject.Parse(temp_response);
        //            if (Convert.ToString(obj2.error_code) == "1")
        //            {
        //                this.ListFetchError = obj2.message;
        //                this.ListFetchStatus = false; // Bad Input?
        //            }
        //            else
        //            {
        //                this.ListFetchStatus = true; // Flag to tell the other methods about Sign up status
        //                try
        //                {
        //                    dynamic dynObj = JsonConvert.DeserializeObject(Convert.ToString(obj2.records));

        //                    foreach (var data in dynObj)
        //                    {

        //                        models.ListOfBanks._allEntriesList_Private.Clear();

        //                        foreach (var item in obj2.records)
        //                        {
        //                            //Console.WriteLine("Bank : " + Convert.ToString(item.b_id));
        //                            models.ListOfBanks._allEntriesList_Private.Add(
        //                                new models.ListOfBanks
        //                                {
        //                                    b_id = Convert.ToString(data.b_id),
        //                                    b_name = Convert.ToString(data.b_name),
        //                                    b_state = Convert.ToString(data.b_state),
        //                                    b_country = Convert.ToString(data.b_country),
        //                                    b_address = Convert.ToString(data.b_address),

        //                                    b_A_neg = Convert.ToString(data.b_A_neg),
        //                                    b_A_pos = Convert.ToString(data.b_A_pos),
        //                                    b_B_neg = Convert.ToString(data.b_B_neg),
        //                                    b_B_pos = Convert.ToString(data.b_B_pos),

        //                                    b_AB_neg = Convert.ToString(data.b_AB_neg),
        //                                    b_AB_pos = Convert.ToString(data.b_AB_pos),
        //                                    b_O_neg = Convert.ToString(data.b_O_neg),
        //                                    b_O_pos = Convert.ToString(data.b_O_pos),
        //                                });
        //                        }

        //                        foreach (var item in models.ListOfBanks._allEntriesList_Private)
        //                        {
        //                            Console.WriteLine("Bank 2 : " + Convert.ToString(item.b_id));
        //                        }

        //                    }
        //                    return;

        //                }
        //                catch (Exception BadUserID)
        //                {
        //                    Console.WriteLine("Exception BadUserID Country : " + BadUserID);
        //                    await DisplayAlert("Bad Request", "Wrong User ID", "Ok");
        //                }
        //            }

        //        }
        //        catch (Exception)
        //        {
        //            await DisplayAlert("No Internet", "Could Not Connect To Internet", "Ok");
        //        }
        //    }
        //    catch (Exception)
        //    {
        //        await DisplayAlert("No Internet", "Could Not Connect To Internet", "Ok");
        //    }
        //}
    }
}