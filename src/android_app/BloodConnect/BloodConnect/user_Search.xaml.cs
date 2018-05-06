using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Linq;
using System.Net.Http;
using System.Text;
using System.Threading.Tasks;

using Xamarin.Forms;
using Xamarin.Forms.Xaml;

namespace BloodConnect
{
	[XamlCompilation(XamlCompilationOptions.Compile)]
	public partial class user_Search : ContentPage
	{
        private HttpClient _client = new HttpClient();
        List<string> listOfStates;
        private string ListFetchError;
        private bool ListFetchStatus;
        private ObservableCollection<models.ListOfBanks> _allEntriesList_Private = new ObservableCollection<models.ListOfBanks> { };
        //public ObservableCollection<string> Items { get; set; }
        //private static ObservableCollection<models.ListOfBanks> _allEntriesList_Private = new ObservableCollection<models.ListOfBanks> { };

        public user_Search ()
		{
			InitializeComponent ();
            //usersPastEntries_ListView.ItemsSource = models.ListOfBanks._allEntriesList_Private;
        }

        private async void user_SearchFilter(object sender, EventArgs e)
        {
            //DisplayAlert("FAB Clicked", "Fab Clicked", "Ok", "Cancel");
            var page = new NavigationPage(new user_SearchFilter());
            await Navigation.PushModalAsync(page);
        }

        protected async override void OnAppearing()
        {
            if (!string.IsNullOrEmpty(models.GlobalProps.search_bloodType))
            {
                await Task.Run(() => BankListFetcher());

                if (_allEntriesList_Private.Count > 0)
                {
                    user_SearchEmptyScreen.IsVisible = false;
                    user_SearchList.IsVisible = true;
                    //usersPastEntries_ListView.IsVisible = true;
                }
                foreach (var item in _allEntriesList_Private)
                {
                    Console.WriteLine("Bank 3 : " + Convert.ToString(item.b_id));
                }
            }
            
            usersPastEntries_ListView.ItemsSource = _allEntriesList_Private;
            usersPastEntries_ListView.EndRefresh();
        }

        private async void usersAllEntries_ItemSelected(object sender, SelectedItemChangedEventArgs e)
        {
            if (e.SelectedItem == null)
                return;

            var entrySelected = e.SelectedItem as models.ListOfBanks;

            await DisplayAlert("Address", "Address : " + entrySelected.b_address, "ok");
            usersPastEntries_ListView.SelectedItem = null;

        }

        private async Task BankListFetcher()
        {
            try
            {
                var data_to_send = new FormUrlEncodedContent(new[]
                {
                    new KeyValuePair<string, string>("blood_type", models.GlobalProps.search_bloodType),
                    new KeyValuePair<string, string>("state", models.GlobalProps.search_state),
                    new KeyValuePair<string, string>("country", models.GlobalProps.search_country),
                    new KeyValuePair<string, string>("donation_amount", models.GlobalProps.search_donation_amount)
                });
                var content = await _client.PostAsync(MainPage.primaryDomain + "/BloodConnect/blood_bank/list_of_banks.php", data_to_send);

                var temp_response = await content.Content.ReadAsStringAsync();

                try
                {
                    dynamic obj2 = Newtonsoft.Json.Linq.JObject.Parse(temp_response);
                    if (Convert.ToString(obj2.error_code) == "1")
                    {
                        this.ListFetchError = obj2.message;
                        this.ListFetchStatus = false; // Bad Input?
                    }
                    else
                    {
                        this.ListFetchStatus = true; // Flag to tell the other methods about Sign up status
                        try
                        {
                            dynamic dynObj = JsonConvert.DeserializeObject(Convert.ToString(obj2.records));

                            foreach (var data in dynObj)
                            {

                                _allEntriesList_Private.Clear();

                                foreach (var item in obj2.records)
                                {
                                    Console.WriteLine("My Bank : " + Convert.ToString(item.b_id));
                                    _allEntriesList_Private.Add(
                                        new models.ListOfBanks
                                        {
                                            b_id = Convert.ToString(item.b_id),
                                            b_name = Convert.ToString(item.b_name),
                                            b_state = Convert.ToString(item.b_state),
                                            b_country = Convert.ToString(item.b_country),
                                            b_address = Convert.ToString(item.b_address),

                                            b_A_neg = Convert.ToString(item.b_A_neg),
                                            b_A_pos = Convert.ToString(item.b_A_pos),
                                            b_B_neg = Convert.ToString(item.b_B_neg),
                                            b_B_pos = Convert.ToString(item.b_B_pos),

                                            b_AB_neg = Convert.ToString(item.b_AB_neg),
                                            b_AB_pos = Convert.ToString(item.b_AB_pos),
                                            b_O_neg = Convert.ToString(item.b_O_neg),
                                            b_O_pos = Convert.ToString(item.b_O_pos),
                                        });
                                }

                                foreach (var item in _allEntriesList_Private)
                                {
                                    Console.WriteLine("Bank 2 : " + Convert.ToString(item.b_id));
                                }

                            }
                            return;

                        }
                        catch (Exception BadUserID)
                        {
                            Console.WriteLine("Exception BadUserID Country : " + BadUserID);
                            await DisplayAlert("Bad Request", "Wrong User ID", "Ok");
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
    }
}