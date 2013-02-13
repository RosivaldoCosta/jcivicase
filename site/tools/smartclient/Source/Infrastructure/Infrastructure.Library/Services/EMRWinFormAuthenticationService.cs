using System;
using Microsoft.Practices.CompositeUI.Services;
using Sante.EMR.SmartClient.Infrastructure.Interface.Services;
using Microsoft.Practices.CompositeUI;
using Microsoft.Practices.ObjectBuilder;
using System.Security.Authentication;
using Sante.EMR.SmartClient.Infrastructure.Library.Properties;
using Microsoft.Practices.SmartClient.EnterpriseLibrary;
using System.Security.Principal;
using System.Threading;

namespace Sante.EMR.SmartClient.Infrastructure.Library.Services
{
    class EMRWinFormAuthenticationService : IAuthenticationService
    {
        private IUserSelectorService _userSelector;
        

        [InjectionConstructor]
        public EMRWinFormAuthenticationService([ServiceDependency] IUserSelectorService userSelector)
        {
            _userSelector = userSelector;

           
        }

        #region IAuthenticationService Members

        public void Authenticate()
        {
            IUserData user = _userSelector.SelectUser();

            if (user != null)
            {
                GenericIdentity identity = new GenericIdentity(user.Name);
                GenericPrincipal principal = new GenericPrincipal(identity, user.Roles);
                Thread.CurrentPrincipal = principal;
            }
            else
            {
               throw new AuthenticationException(Resources.NoUserProvidedForAuthentication);
            }
        }

        #endregion
    }
}
