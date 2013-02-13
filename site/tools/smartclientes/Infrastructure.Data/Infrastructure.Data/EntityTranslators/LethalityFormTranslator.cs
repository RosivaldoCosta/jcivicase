using System;
using System.Collections.Generic;
using System.Text;
using Sante.EMR.SmartClient.Infrastructure.Library.EntityTranslators;
using BusinessEntitiesAlias = Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities;
using DataWebServiceAlias = Sante.EMR.SmartClient.Infrastructure.Data.DataWebService;
using Sante.EMR.SmartClient.Infrastructure.Interface.Services;
using System.Reflection;


namespace Sante.EMR.SmartClient.Infrastructure.Data.EntityTranslators
{
    public class LethalityFormTranslator : EntityMapperTranslator<BusinessEntitiesAlias.Lethality, DataWebServiceAlias.Lethality>
    {
        /// <summary>
        /// 
        /// </summary>
        /// <param name="service"></param>
        /// <param name="value"></param>
        /// <returns></returns>
        protected override DataWebServiceAlias.Lethality BusinessToService(IEntityTranslatorService service, BusinessEntitiesAlias.Lethality value)
        { 
            DataWebServiceAlias.Lethality l = new DataWebServiceAlias.Lethality();
            foreach (PropertyInfo source in value.GetType().GetProperties())
            {
                foreach (PropertyInfo target in l.GetType().GetProperties())
                {
                    if (target.Name.Equals(source.Name))
                    {
                        object o = source.GetValue(value, null);

                        target.GetSetMethod().Invoke(l, new object[] { o });
                    }
                }
            }

           
           

            return l;
        }

        /// <summary>
        /// 
        /// </summary>
        /// <param name="service"></param>
        /// <param name="value"></param>
        /// <returns></returns>
        protected override BusinessEntitiesAlias.Lethality ServiceToBusiness(IEntityTranslatorService service, DataWebServiceAlias.Lethality value)
        {
            BusinessEntitiesAlias.Lethality l = new BusinessEntitiesAlias.Lethality();
            foreach (PropertyInfo source in value.GetType().GetProperties())
            {
                foreach (PropertyInfo target in l.GetType().GetProperties())
                {
                    if (target.Name.Equals(source.Name))
                    {
                        object o = source.GetValue(value, null);

                        target.GetSetMethod().Invoke(l, new object[] { o });
                    }
                }
            }


            return l;
        }
    }
}
