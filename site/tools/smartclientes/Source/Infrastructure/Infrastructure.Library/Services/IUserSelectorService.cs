using System;
using System.Collections.Generic;
using System.Text;

namespace Sante.EMR.SmartClient.Infrastructure.Library.Services
{
    public interface IUserSelectorService
    {
        IUserData SelectUser();
    }
}
