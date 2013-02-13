using System;
using System.Collections.Generic;
using System.Text;
using Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities;

namespace Sante.EMR.SmartClient.Module.Services
{
    public interface ISerializationService
    {
        void Serialize(object form);
        object Deserialize();
    }
}
