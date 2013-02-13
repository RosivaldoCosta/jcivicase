using System;
using System.Collections.Generic;
using System.Text;
using Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities;

namespace Sante.EMR.SmartClient.Infrastructure.Data.Services
{
    interface IRestService
    {
        bool sendForm(Form f, RestMethod method);
    }
}
