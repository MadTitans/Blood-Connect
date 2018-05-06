using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Text;

namespace BloodConnect.models
{
    class ListOfBanks
    {
        public string b_id { get; set; }
        public string b_name { get; set; }
        public string b_state { get; set; }
        public string b_country { get; set; }
        public string b_address { get; set; }

        public string b_A_neg { get; set; }
        public string b_A_pos { get; set; }
        public string b_B_neg { get; set; }
        public string b_B_pos { get; set; }

        public string b_AB_neg { get; set; }
        public string b_AB_pos { get; set; }
        public string b_O_neg { get; set; }
        public string b_O_pos { get; set; }

        //public static ObservableCollection<models.ListOfBanks> _allEntriesList_Private = new ObservableCollection<models.ListOfBanks> { };
    }
}
