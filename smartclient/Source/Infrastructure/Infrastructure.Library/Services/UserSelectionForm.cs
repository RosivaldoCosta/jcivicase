using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;
//using DevExpress.XtraEditors;
using Sante.EMR.SmartClient.Infrastructure.Interface.Services;
using Sante.EMR.SmartClient.Infrastructure.Library.Services;


namespace Sante.EMR.SmartClient.Infrastructure.Interface.WinForms
{
    public partial class UserSelectionForm : Form
    {
        private UserData[] _users;
        private UserData _matchUser;
        
        //public UserSelectionForm()
        //{
        //    InitializeComponent();
        //}

        public UserSelectionForm(UserData[] userdata) 
        {
            _users = userdata;
            InitializeComponent();
        }

        /// <summary>
        /// 
        /// </summary>
        /// <returns></returns>
        public UserData SelectUser()
        {
            if (DialogResult.OK == ShowDialog())
            {
                return _matchUser;
            }

            return null;
        }

        private void LoginBtn_Click(object sender, EventArgs e)
        {
            UserData match = Array.Find<UserData>(_users, delegate(UserData test)
            {
                return String.Compare(test.Name, UserNameTextEdit.Text, StringComparison.CurrentCulture) == 0 &&
                    String.Compare(test.Password, PasswordTextEdit.Text, StringComparison.CurrentCulture) == 0;
            });

            if (match == null)
            {
                _messageLabel.Text = Sante.EMR.SmartClient.Infrastructure.Library.Properties.Resources.UserNotFoundMessage;
            }
            else
            {
                _matchUser = match;
                this.DialogResult = DialogResult.OK;
                Close();
            }
        }


        public bool IsAuthenticated()
        {
            //throw new Exception("The method or operation is not implemented.");
            return true;
        }

        private void button1_Click(object sender, EventArgs e)
        {
            UserData match = Array.Find<UserData>(_users, delegate(UserData test)
           {
               return String.Compare(test.Name, UserNameTextEdit.Text, StringComparison.CurrentCulture) == 0 &&
                   String.Compare(test.Password, PasswordTextEdit.Text, StringComparison.CurrentCulture) == 0;
           });

            if (match == null)
            {
                _messageLabel.Text = Sante.EMR.SmartClient.Infrastructure.Library.Properties.Resources.UserNotFoundMessage;
            }
            else
            {
                _matchUser = match;
                this.DialogResult = DialogResult.OK;
                Close();
            }
        }

        private void UserSelectionForm_Load(object sender, EventArgs e)
        {
#if DEBUG
            UserNameTextEdit.Text = "Sante";
            PasswordTextEdit.Text = "ASG123";
#endif
        }
    }
}