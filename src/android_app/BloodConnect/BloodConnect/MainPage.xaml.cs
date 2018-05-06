using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace BloodConnect
{
	public partial class MainPage : ContentPage
	{
        public static string primaryDomain = "https://xonshiz.heliohost.org";

        public MainPage()
		{
			InitializeComponent();
		}

        async void button_GetClicked(Object sender, EventArgs e)
        {
            //DisplayAlert("Title", "Clicked you", "Ok");
            //grid_UserLoginLayout.Isvisible = false;
            await Navigation.PushAsync(new donor_authorityAuth());
        }
    }
}
