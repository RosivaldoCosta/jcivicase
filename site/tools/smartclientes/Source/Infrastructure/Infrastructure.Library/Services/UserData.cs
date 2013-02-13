using System;
using System.Collections.Generic;
using System.Text;
using Sante.EMR.SmartClient.Infrastructure.Interface.Services;

namespace Sante.EMR.SmartClient.Infrastructure.Library.Services
{
    public class UserData : IUserData
    {
        private string _username;
        private string _password;
        private string[] _roles;

        public string[] Roles
        {
            get
            {
                return _roles;
            }

            set
            {
            	_roles = value;
            }
        }

        public string Name
        {
            get
            {
                return _username;
            }

            set
            {
            	_username = value;
            }
        }

        public string Password 
        {
            get
            {
                return _password;
            }

            set
            {
            	_password = value;
            }
        }

    }
}
