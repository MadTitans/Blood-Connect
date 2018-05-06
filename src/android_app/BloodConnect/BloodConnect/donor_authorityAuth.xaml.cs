using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

using Xamarin.Forms;
using Xamarin.Forms.Xaml;

namespace BloodConnect
{
	[XamlCompilation(XamlCompilationOptions.Compile)]
	public partial class donor_authorityAuth : TabbedPage
    {
		public donor_authorityAuth ()
		{
			InitializeComponent ();
		}

        async void aboutUs_Activated(Object sender, System.EventArgs e)
        {
            await DisplayAlert("Title", "Clicked you", "Ok");
            //await Navigation.PushAsync(new userAuthority_LoginSignup());
            //await Navigation.PushAsync(new Application_aboutUs());
        }
    }
}