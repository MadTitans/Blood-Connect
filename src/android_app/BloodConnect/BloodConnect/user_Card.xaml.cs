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
	public partial class user_Card : ContentPage
	{
		public user_Card ()
		{
			InitializeComponent ();

            user_CardName.Text = models.LoggedInUserData.u_name;
            user_CardBloodGroup.Text = models.LoggedInUserData.u_blood_type;
            user_CardUID.Text = models.LoggedInUserData.u_UIDAI;
            user_CardQRCode.Source = models.LoggedInUserData.u_qr_link;

            user_CardEmailID.Text = models.LoggedInUserData.u_email;
            user_CardPhoneNumber.Text = models.LoggedInUserData.u_phone_number;
            user_CardAddress.Text = models.LoggedInUserData.u_address;

        }
	}
}