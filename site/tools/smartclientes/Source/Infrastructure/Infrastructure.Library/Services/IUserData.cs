using System;
using System.Collections.Generic;
using System.Text;

namespace Sante.EMR.SmartClient.Infrastructure.Library.Services
{
    public interface IUserData
    {
        
            string Name { get; set; }
            string Password { get; set; }
            string[] Roles { get; set; }
        
    }
}
