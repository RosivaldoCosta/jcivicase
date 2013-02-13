using System;
using System.Collections.Generic;
using System.Text;
using Sante.EMR.SmartClient.Infrastructure.Library.Services;
using Sante.EMR.SmartClient.Infrastructure.Interface.WinForms;
using Microsoft.Practices.SmartClient.EnterpriseLibrary;
using Microsoft.Practices.CompositeUI;
using Sante.EMR.SmartClient.Infrastructure.Module.ConnectionMonitor.Services;
using Microsoft.Practices.SmartClient.ConnectionMonitor;
//using Microsoft.Practices.SmartClient.DisconnectedAgent;

 
namespace Sante.EMR.SmartClient.Infrastructure.Library.Services
{

	public class UserSelectorService : IUserSelectorService
	{
        private Microsoft.Practices.SmartClient.ConnectionMonitor.ConnectionMonitor _networkMonitor;
        
        private UserData[] _users;
        
		public UserSelectorService()
		{
            _networkMonitor = ConnectionMonitorFactory.CreateFromConfiguration();
            
       		_users = GetUsers();
		}

		public IUserData SelectUser()
		{
			UserSelectionForm form = new UserSelectionForm(_users);
			return form.SelectUser();
		}


		private UserData[] GetUsers()
		{
            UserData[] u;

            //if (_networkMonitor.IsConnected)
            //{
            //    u = new UserData[1];
            //    u[0] = new UserData();
            //    u[0].Name = "Sante";
            //    u[0].Password = "ASG123";
            //}
            //else
            //{
                u = new UserData[1];
                u[0] = new UserData();
                u[0].Name = "Sante";
                u[0].Password = "ASG123";
            //}
            
            return u;
		}
	}

 
}


