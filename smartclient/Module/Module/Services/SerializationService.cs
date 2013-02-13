using System;
using System.Collections.Generic;
using System.Text;
using Microsoft.Practices.CompositeUI;
using System.Runtime.Serialization.Formatters.Binary;
using System.IO;
using Sante.EMR.SmartClient.Infrastructure.Logger.Services;
using Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities;

namespace Sante.EMR.SmartClient.Module.Services
{
    [Service(typeof(ISerializationService))]
    public class SerializationService : ISerializationService
    {
        private Stream _file; // = File.OpenWrite(AppDomain.CurrentDomain.BaseDirectory);
        private BinaryFormatter _bf = new BinaryFormatter();
        private ILoggerService _logger;
        public SerializationService([ServiceDependency] ILoggerService logger)
        {
            _logger = logger;
            
        }
        #region ISerializationService Members

        /// <summary>
        /// 
        /// </summary>
        /// <param name="o"></param>
        public void Serialize(object o)
        {
            try
            {
                _file = File.OpenWrite(AppDomain.CurrentDomain.BaseDirectory + "\\out.bin");
                _bf.Serialize(_file, o);
            }
            catch (Exception e)
            {
                _logger.Write(e.Message);
                _logger.Write(e.StackTrace);
            }
        }

        public object Deserialize()
        {
            throw new Exception("The method or operation is not implemented.");
        }

        #endregion
    }
}
